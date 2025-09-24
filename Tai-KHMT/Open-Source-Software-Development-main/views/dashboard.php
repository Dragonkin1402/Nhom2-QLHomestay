<?php
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');

// Chỉ cho phép admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php?error=Bạn không có quyền truy cập");
    exit;
}



require_once __DIR__ . '/../functions/stats_functions.php';

$roomStats = getRoomStats();
$revenue = getRevenueStats();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Thống kê - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/menu.php'; ?>

<div class="container mt-4">
    <h3>📊 Thống kê hệ thống</h3>
    <div class="row">
        <div class="col-md-3">
            <div class="card text-center bg-primary text-white">
                <div class="card-body">
                    <h5>Tổng số phòng</h5>
                    <p class="fs-3"><?= $roomStats['total'] ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <h5>Phòng trống</h5>
                    <p class="fs-3"><?= $roomStats['available'] ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-warning text-dark">
                <div class="card-body">
                    <h5>Phòng đã đặt</h5>
                    <p class="fs-3"><?= $roomStats['occupied'] ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-danger text-white">
                <div class="card-body">
                    <h5>Doanh thu</h5>
                    <p class="fs-3"><?= number_format($revenue, 0, ',', '.') ?> VND</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
