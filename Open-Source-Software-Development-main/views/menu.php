<?php
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');
$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .navbar {
            background: linear-gradient(90deg, #0d6efd, #4dabf7);
        }
        .navbar .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 6px;
            border-radius: 6px;
            transition: all 0.2s;
        }
        .navbar .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        .navbar .nav-link.active {
            background: white;
            color: #0d6efd !important;
        }
        .user-info img {
            border: 2px solid #fff;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg shadow-sm">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center text-white fw-bold" href="#">
                <img src="../images/fitdnu_logo.png" alt="FIT-DNU Logo" height="40" class="me-2">
                DNU Homestay
            </a>

            <!-- Toggle button -->
            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#navbarNav" aria-controls="navbarNav" 
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu items -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php if (($currentUser['role'] ?? '') === 'admin'): ?>
                           <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">📊 Thống kê</a>
                    </li>
                        <li class="nav-item">
                            <a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='customer.php'?'active':'' ?>" href="customer.php">
                                <i class="bi bi-people-fill"></i> Khách hàng
                            </a>
                      
                        
                    <?php endif; ?>

                    <!-- Menu chung cho cả Admin và User -->
                     <li class="nav-item">
                            <a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='room.php'?'active':'' ?>" href="room.php">
                                <i class="bi bi-house-door-fill"></i> Phòng
                            </a>
                        </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='booking.php'?'active':'' ?>" href="booking.php">
                            <i class="bi bi-calendar-check-fill"></i> Booking
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF'])=='payment.php'?'active':'' ?>" href="payment.php">
                            <i class="bi bi-credit-card-2-front-fill"></i> Thanh toán
                        </a>
                    </li>
                      </li>
                     
                    
                </ul>
                                
                   

                <!-- User info + Logout -->
                <div class="d-flex align-items-center text-white">
                    <img src="../images/aiotlab_logo.png" alt="User" width="36" height="36" class="rounded-circle me-2">
                    <strong><?= htmlspecialchars($currentUser['username'] ?? 'User') ?></strong>
                    <span class="badge bg-light text-dark ms-2"><?= htmlspecialchars($currentUser['role'] ?? '') ?></span>
                    <a class="btn btn-sm btn-danger ms-3" href="../handle/logout_process.php">
                        <i class="bi bi-box-arrow-right"></i> Đăng xuất
                    </a>
                </div>

            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
