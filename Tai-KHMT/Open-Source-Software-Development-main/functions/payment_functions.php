<?php
require_once 'db_connection.php';

/**
 * Lấy tất cả payments (cho admin)
 */
function getAllPayments() {
    $conn = getDbConnection();
    $sql = "SELECT p.id, p.booking_id, p.amount, p.method, p.date, u.username
            FROM payments p
            JOIN bookings b ON p.booking_id = b.id
            JOIN customers c ON b.customer_id = c.id
            JOIN users u ON c.user_id = u.id
            ORDER BY p.id DESC";
    $result = mysqli_query($conn, $sql);

    $payments = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $payments[] = $row;
        }
    }

    mysqli_close($conn);
    return $payments;
}

/**
 * Lấy payments theo user_id (cho user thường)
 */
function getPaymentsByUser($user_id) {
    $conn = getDbConnection();
    $sql = "SELECT p.id, p.booking_id, p.amount, p.method, p.date
            FROM payments p
            JOIN bookings b ON p.booking_id = b.id
            JOIN customers c ON b.customer_id = c.id
            WHERE c.user_id = ?
            ORDER BY p.id DESC";
    $stmt = mysqli_prepare($conn, $sql);

    $payments = [];
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $payments[] = $row;
            }
        }
        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
    return $payments;
}

/**
 * Thêm payment mới
 */
function addPayment($booking_id, $amount, $method, $date) {
    $conn = getDbConnection();

    $sql = "INSERT INTO payments (booking_id, amount, method, date) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "idss", $booking_id, $amount, $method, $date);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Nếu thanh toán thành công thì update trạng thái phòng
        if ($success) {
            $updateRoom = "
                UPDATE rooms r
                JOIN bookings b ON r.id = b.room_id
                SET r.status = 'occupied'
                WHERE b.id = ?
            ";
            $stmt2 = mysqli_prepare($conn, $updateRoom);
            mysqli_stmt_bind_param($stmt2, "i", $booking_id);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);
        }

        mysqli_close($conn);
        return $success;
    }

    mysqli_close($conn);
    return false;
}

/**
 * Lấy payment theo ID
 */
function getPaymentById($id) {
    $conn = getDbConnection();
    $sql = "SELECT * FROM payments WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $payment = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $payment;
        }
        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
    return null;
}

/**
 * Cập nhật payment
 */
function updatePayment($id, $booking_id, $amount, $method, $date) {
    $conn = getDbConnection();

    // 1. Cập nhật payment
    $sql = "UPDATE payments SET booking_id = ?, amount = ?, method = ?, date = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "idssi", $booking_id, $amount, $method, $date, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($success) {
            // 2. Nếu update payment OK thì đổi trạng thái phòng
            $updateRoom = "
                UPDATE rooms r
                JOIN bookings b ON r.id = b.room_id
                SET r.status = 'occupied'
                WHERE b.id = ?
            ";
            $stmt2 = mysqli_prepare($conn, $updateRoom);
            mysqli_stmt_bind_param($stmt2, "i", $booking_id);

            if (!mysqli_stmt_execute($stmt2)) {
                echo "SQL Error: " . mysqli_error($conn);
            } else {
                echo "Update room thành công!";
            }

            mysqli_stmt_close($stmt2);
        }

        mysqli_close($conn);
        return $success;
    }

    mysqli_close($conn);
    return false;
}

/**
 * Xóa payment
 */
function deletePayment($id) {
    $conn = getDbConnection();
    $sql = "DELETE FROM payments WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }

    mysqli_close($conn);
    return false;
}
