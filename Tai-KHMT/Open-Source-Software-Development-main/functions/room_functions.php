<?php
require_once 'db_connection.php';

/**
 * Lấy tất cả danh sách phòng
 * @return array Danh sách phòng
 */
function getAllRooms() {
    $conn = getDbConnection();

    $sql = "SELECT id, name, type, price, status, description FROM rooms ORDER BY id";
    $result = mysqli_query($conn, $sql);

    $rooms = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rooms[] = $row;
        }
    }

    mysqli_close($conn);
    return $rooms;
}

/**
 * Thêm phòng mới
 */
function addRoom($name, $type, $price, $status, $description) {
    $conn = getDbConnection();

    $sql = "INSERT INTO rooms (name, type, price, status, description) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssdss", $name, $type, $price, $status, $description);
        $success = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }

    mysqli_close($conn);
    return false;
}

/**
 * Lấy thông tin phòng theo ID
 */
function getRoomById($id) {
    $conn = getDbConnection();

    $sql = "SELECT id, name, type, price, status, description FROM rooms WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $room = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $room;
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
    return null;
}

/**
 * Cập nhật thông tin phòng
 */
function updateRoom($id, $name, $type, $price, $status, $description) {
    $conn = getDbConnection();

    $sql = "UPDATE rooms SET name = ?, type = ?, price = ?, status = ?, description = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssdssi", $name, $type, $price, $status, $description, $id);
        $success = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }

    mysqli_close($conn);
    return false;
}

/**
 * Xóa phòng theo ID
 */
function deleteRoom($id) {
    $conn = getDbConnection();

    $sql = "DELETE FROM rooms WHERE id = ?";
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

/**
 * Lấy danh sách phòng cho dropdown
 */
function getAllRoomsForDropdown() {
    $conn = getDbConnection();

    $sql = "SELECT id, name FROM rooms ORDER BY name ASC";
    $result = mysqli_query($conn, $sql);

    $rooms = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rooms[] = $row;
        }
    }
    mysqli_close($conn);
    return $rooms;
}
?>
