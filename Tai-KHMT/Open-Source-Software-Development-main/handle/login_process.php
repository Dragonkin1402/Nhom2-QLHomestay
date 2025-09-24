<?php
session_start();
require_once '../functions/db_connection.php';
require_once '../functions/auth.php'; // file này chứa hàm authenticateUser()

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    handleLogin();
}

function handleLogin() {
    $conn = getDbConnection();
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Vui lòng nhập đầy đủ username và password!';
        header('Location: ../index.php');
        exit();
    }

    $user = authenticateUser($conn, $username, $password);
    if ($user) {
        // Lưu session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = strtolower($user['role']); // chuẩn hóa về chữ thường
        $_SESSION['success'] = 'Đăng nhập thành công!';
        mysqli_close($conn);

        // Chuyển hướng theo phân quyền
        if ($_SESSION['role'] === 'admin') {
            header('Location: ../views/customer.php'); // admin quản lý
        } else {
            header('Location: ../views/room.php'); // user chỉ xem/đặt phòng
        }
        exit();
    }

    // Sai tài khoản hoặc mật khẩu
    $_SESSION['error'] = 'Tên đăng nhập hoặc mật khẩu không đúng!';
    mysqli_close($conn);
    header('Location: ../index.php');
    exit();
}
?>
