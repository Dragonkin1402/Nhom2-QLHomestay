<?php
require_once 'db_connection.php';

/**
 * Lấy tất cả danh sách khách hàng
 * @return array Danh sách customers
 */
function getAllCustomers() {
    $conn = getDbConnection();

    $sql = "SELECT id, user_id, name, email, phone FROM customers ORDER BY id";
    $result = mysqli_query($conn, $sql);

    $customers = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) { 
            $customers[] = $row;
        }
    }

    mysqli_close($conn);
    return $customers;
}

/**
 * Thêm khách hàng mới (luôn gắn với user_id)
 * @param int $userId ID của user
 * @param string $name Tên khách hàng
 * @param string $email Email khách hàng
 * @param string $phone Số điện thoại khách hàng
 * @return bool True nếu thành công, False nếu thất bại
 */
function addCustomer($name, $email, $phone, $user_id) {
    $conn = getDbConnection();

    $sql = "INSERT INTO customers (name, email, phone, user_id) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $phone, $user_id);
        $success = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }

    mysqli_close($conn);
    return false;
}


/**
 * Lấy thông tin một khách hàng theo ID
 * @param int $id ID khách hàng
 * @return array|null Thông tin khách hàng hoặc null nếu không tìm thấy
 */
function getCustomerById($id) {
    $conn = getDbConnection();

    $sql = "SELECT id, user_id, name, email, phone FROM customers WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $customer = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            return $customer;
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
    return null;
}

/**
 * Cập nhật thông tin khách hàng
 */
function updateCustomer($id, $name, $email, $phone) {
    $conn = getDbConnection();

    $sql = "UPDATE customers SET name = ?, email = ?, phone = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $phone, $id);
        $success = mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $success;
    }

    mysqli_close($conn);
    return false;
}

/**
 * Xóa khách hàng theo ID
 */
function deleteCustomer($id) {
    $conn = getDbConnection();

    $sql = "DELETE FROM customers WHERE id = ?";
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
 * Lấy danh sách khách hàng cho dropdown
 */
function getAllCustomersForDropdown() {
    $conn = getDbConnection();  

    $sql = "SELECT id, name, email FROM customers ORDER BY name ASC";
    $result = mysqli_query($conn, $sql);

    $customers = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $customers[] = $row;
        }
    }

    mysqli_close($conn); 
    return $customers;
}
?>
