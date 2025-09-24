<?php
require_once __DIR__ . '/../functions/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Lấy dữ liệu từ form
    $username = trim($_POST['username']);
    $rawPassword = $_POST['password'];
    $email = $_POST['email'] ?? null;
    $phone = $_POST['phone'] ?? null;
    $role = "user"; // mặc định user

    // Kết nối DB
    $conn = getDbConnection();

    // 1. Kiểm tra trùng username
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        $conn->close();
        header("Location: ../views/register.php?error=Tên đăng nhập đã tồn tại");
        exit;
    }
    $stmt->close();

    // 2. Mã hóa mật khẩu
    // 2. Không mã hóa, lưu thẳng mật khẩu
    $password = $rawPassword;


    // 3. Thêm vào bảng users
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        $userId = $stmt->insert_id;
        $stmt->close();

        // 4. Đồng bộ sang bảng customers
        $stmt = $conn->prepare("INSERT INTO customers (user_id, name, email, phone) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $userId, $username, $email, $phone);
        $stmt->execute();
        $stmt->close();

        $conn->close();

        // 5. Thành công → quay về login
        header("Location: ../index.php?success=Đăng ký thành công, hãy đăng nhập");
        exit;
    } else {
        $error = "Lỗi khi đăng ký: " . $stmt->error;
        $stmt->close();
        $conn->close();
        header("Location: ../views/register.php?error=" . urlencode($error));
        exit;
    }
}
