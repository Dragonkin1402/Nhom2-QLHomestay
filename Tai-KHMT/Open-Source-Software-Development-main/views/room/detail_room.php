<?php 
require_once __DIR__ . '/../../functions/auth.php'; 
checkLogin(__DIR__ . '/../../index.php'); // bảo vệ trang

require '../../handle/room_process.php'; // file xử lý DB

// Lấy ID phòng từ URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ../room.php');
    exit;
}

$roomId = (int)$_GET['id']; // ép kiểu để an toàn
$room = handleGetRoomById($roomId);

if (!$room) {
    header('Location: room.php?error=Phòng không tồn tại');
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết phòng - <?= htmlspecialchars($room['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
<?php include '../menu.php'; ?>

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
    <h3 class="fw-bold text-primary mb-4">
        <i class="bi bi-house-door-fill"></i> Chi tiết phòng
    </h3>

    <div class="mb-3">
        <strong>Tên phòng:</strong> <?= htmlspecialchars($room['name']) ?>
    </div>
    <div class="mb-3">
        <strong>Loại phòng:</strong> <?= htmlspecialchars($room['type']) ?>
    </div>
    <div class="mb-3">
        <strong>Giá:</strong> <?= number_format($room['price']) ?> VNĐ
    </div>
    <div class="mb-3">
        <strong>Trạng thái:</strong> 
        <?php
            if ($room["status"] === "available") echo '<span class="badge bg-success">Còn trống</span>';
            elseif ($room["status"] === "booked") echo '<span class="badge bg-warning text-dark">Đã đặt phòng</span>';
            elseif ($room["status"] === "occupied") echo '<span class="badge bg-primary">Đang ở</span>';
            else echo '<span class="badge bg-secondary">Bảo trì</span>';
        ?>
    </div>
    <div class="mb-3">
        <strong>Mô tả phòng:</strong><br>
        <div class="p-2 border rounded bg-light">
            <?= !empty($room['description']) 
                ? nl2br(htmlspecialchars($room['description'])) 
                : '<em>Chưa có thông tin mô tả</em>' ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="../room.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
    </div>
</div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
