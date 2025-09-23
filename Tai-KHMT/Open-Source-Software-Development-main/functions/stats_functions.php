<?php
require_once 'db_connection.php';

/**
 * Lấy thống kê phòng
 * - tổng số phòng
 * - phòng trống (status = available)
 * - phòng đã đặt (status = occupied)
 */
function getRoomStats() {
    $conn = getDbConnection();

    $sql = "
        SELECT 
            COUNT(*) AS total,
            SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) AS available,
            SUM(CASE WHEN status = 'occupied' THEN 1 ELSE 0 END) AS occupied
        FROM rooms
    ";

    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    mysqli_close($conn);

    return $row ? $row : ['total' => 0, 'available' => 0, 'occupied' => 0];
}

/**
 * Lấy doanh thu
 * - tổng cộng tất cả payments.amount
 */
function getRevenueStats() {
    $conn = getDbConnection();

    $sql = "SELECT SUM(amount) AS revenue FROM payments";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    mysqli_close($conn);

    return $row && $row['revenue'] ? $row['revenue'] : 0;
}
