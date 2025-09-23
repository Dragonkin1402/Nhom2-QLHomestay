<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Hệ thống quản lý Homestay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: #f5f7fa;
            font-family: "Segoe UI", sans-serif;
        }
        .login-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            max-width: 1200px;
            width: 100%;
        }
        .login-left {
            background: url("https://dsdhome.vn/wp-content/uploads/2025/06/nha-lap-ghep-200-trieu-compressed.jpg") no-repeat center center;
            background-size: cover;
            min-height:600px;
        }
        .login-right {
            padding: 40px;
        }
        h2 {
            background: linear-gradient(45deg, #007bff, #6f42c1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
            text-align: center;
            margin-bottom: 25px;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            background: linear-gradient(45deg, #007bff, #6f42c1);
            border: none;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-login:hover {
            background: linear-gradient(45deg, #6f42c1, #007bff);
            transform: scale(1.05);
        }
        footer {
            background: #f1f1f1;
            padding: 10px;
            text-align: center;
            font-size: 14px;
            color: #555;
        }
        section {
                height: 100vh; /* chiếm toàn bộ chiều cao màn hình */
                display: flex;
                justify-content: center; /* căn giữa theo chiều ngang */
                align-items: center;     /* căn giữa theo chiều dọc */
            }

    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card row g-0">
            <!-- Hình ảnh -->
            <div class="col-md-6 login-left"></div>
            <!-- Form login -->
            <div class="col-md-6 login-right">
                <h2>Quản Lý Hệ Thống Homestay</h2>
                <form action="./handle/login_process.php" method="POST">
                    <!-- Username -->
                    <div class="mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Tên đăng nhập" required>
                    </div>
                    <!-- Password -->
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
                    </div>
                    
                    <!-- Thông báo lỗi/thành công -->
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                    <?php endif; ?>

                    <button type="submit" name="login" class="btn-login">Đăng nhập</button>
                  <p>Chưa có tài khoản? 
                    <a href="views/register.php">Đăng ký ngay</a>
                </p>


                </form>
            </div>
        </div>
    </div>

    <footer>
        Copyright © 2025 - FITDNU
    </footer>
</body>
</html>
