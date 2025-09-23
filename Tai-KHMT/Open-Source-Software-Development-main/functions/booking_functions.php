<?php
require_once 'db_connection.php';

/**
 * Lấy tất cả danh sách booking (kèm tên khách và tên phòng)
 * Nếu role = user => chỉ lấy booking của chính user_id đó
 * Nếu role = admin => lấy toàn bộ
 */
function getAllBookings($role = 'admin', $user_id = null) {
    $conn = getDbConnection();

    $sql = "SELECT b.id, c.name AS customer_name, r.name AS room_name, b.checkin, b.checkout, b.status
            FROM bookings b
            JOIN customers c ON b.customer_id = c.id
            JOIN rooms r ON b.room_id = r.id";

    // Nếu là user thì chỉ lấy booking của user đó
    if ($role === 'user' && $user_id !== null) {
        $sql .= " WHERE c.user_id = ?";
    }

    $sql .= " ORDER BY b.id DESC";

    if ($role === 'user' && $user_id !== null) {
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = mysqli_query($conn, $sql);
    }

    $bookings = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $bookings[] = $row;
        }
        mysqli_free_result($result);
    }

    if (isset($stmt)) mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $bookings;
}

/**
 * Thêm booking mới
 */
function addBooking($customer_id, $room_id, $checkin, $checkout, $status = 'pending') {
    $conn = getDbConnection();

    $sql = "INSERT INTO bookings (customer_id, room_id, checkin, checkout, status)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log("addBooking prepare failed: " . mysqli_error($conn));
        mysqli_close($conn);
        return false;
    }

    mysqli_stmt_bind_param($stmt, "iisss", $customer_id, $room_id, $checkin, $checkout, $status);
    $ok = mysqli_stmt_execute($stmt);

    if ($ok) {
        $booking_id = mysqli_insert_id($conn);
    } else {
        error_log("addBooking execute failed: " . mysqli_stmt_error($stmt));
        $booking_id = false;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $booking_id;
}

/**
 * Lấy danh sách booking kèm giá phòng (dành cho dropdown / tạo payment)
 */
function getAllBookingsWithRoom($role = 'admin', $user_id = null) {
    $conn = getDbConnection();

    $sql = "SELECT b.id, c.name AS customer_name, r.price AS room_price
            FROM bookings b
            JOIN customers c ON b.customer_id = c.id
            JOIN rooms r ON b.room_id = r.id";

    if ($role === 'user' && $user_id !== null) {
        $sql .= " WHERE c.user_id = ?";
    }

    $sql .= " ORDER BY b.id DESC";

    if ($role === 'user' && $user_id !== null) {
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = mysqli_query($conn, $sql);
    }

    $rows = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row['room_price'] = 0 + $row['room_price']; // cast numeric
            $rows[] = $row;
        }
        mysqli_free_result($result);
    }

    if (isset($stmt)) mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $rows;
}

/**
 * Lấy thông tin booking theo ID
 */
function getBookingById($id) {
    $conn = getDbConnection();

    $sql = "SELECT b.*, c.name AS customer_name, c.email AS customer_email, c.phone AS customer_phone,
                   r.name AS room_name, r.price AS room_price
            FROM bookings b
            JOIN customers c ON b.customer_id = c.id
            JOIN rooms r ON b.room_id = r.id
            WHERE b.id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log("getBookingById prepare failed: " . mysqli_error($conn));
        mysqli_close($conn);
        return null;
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $booking = null;
    if ($result && mysqli_num_rows($result) > 0) {
        $booking = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $booking;
}

/**
 * Cập nhật booking
 */
function updateBooking($id, $customer_id, $room_id, $checkin, $checkout, $status) {
    $conn = getDbConnection();

    $sql = "UPDATE bookings 
            SET customer_id = ?, room_id = ?, checkin = ?, checkout = ?, status = ?
            WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        error_log("updateBooking prepare failed: " . mysqli_error($conn));
        mysqli_close($conn);
        return false;
    }

    mysqli_stmt_bind_param($stmt, "iisssi", $customer_id, $room_id, $checkin, $checkout, $status, $id);
    $success = mysqli_stmt_execute($stmt);
    if (!$success) {
        error_log("updateBooking execute failed: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
}

/**
 * Xóa booking
 */
function deleteBooking($id) {
    $conn = getDbConnection();

    if (!mysqli_begin_transaction($conn)) {
        error_log("deleteBooking: begin transaction failed: " . mysqli_error($conn));
        mysqli_close($conn);
        return false;
    }

    try {
        $sql1 = "DELETE FROM payments WHERE booking_id = ?";
        $stmt1 = mysqli_prepare($conn, $sql1);
        if (!$stmt1) throw new Exception("prepare payments delete failed: " . mysqli_error($conn));
        mysqli_stmt_bind_param($stmt1, "i", $id);
        if (!mysqli_stmt_execute($stmt1)) {
            $err = mysqli_stmt_error($stmt1);
            mysqli_stmt_close($stmt1);
            throw new Exception("execute payments delete failed: " . $err);
        }
        mysqli_stmt_close($stmt1);

        $sql2 = "DELETE FROM bookings WHERE id = ?";
        $stmt2 = mysqli_prepare($conn, $sql2);
        if (!$stmt2) throw new Exception("prepare bookings delete failed: " . mysqli_error($conn));
        mysqli_stmt_bind_param($stmt2, "i", $id);
        if (!mysqli_stmt_execute($stmt2)) {
            $err = mysqli_stmt_error($stmt2);
            mysqli_stmt_close($stmt2);
            throw new Exception("execute bookings delete failed: " . $err);
        }
        mysqli_stmt_close($stmt2);

        mysqli_commit($conn);
        mysqli_close($conn);
        return true;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        error_log("deleteBooking failed: " . $e->getMessage());
        mysqli_close($conn);
        return false;
    }
}
?>
