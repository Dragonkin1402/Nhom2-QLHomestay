<?php 
require_once __DIR__ . '/../functions/auth.php'; 
checkLogin(__DIR__ . '/../index.php'); 
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Homestay - Danh sách phòng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; }
        .card { border-radius: 15px; }
        .table thead th { position: sticky; top: 0; background-color: #0d6efd; color: white; z-index: 10; }
        .action-btn { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; }
        .action-btn i { font-size: 1rem; }
    </style>
</head>
<body>
<?php include './menu.php'; ?>

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Tiêu đề + Nút thêm (chỉ Admin) -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-primary m-0">
                    <i class="bi bi-house-door-fill"></i> Danh sách phòng
                </h3>
                <?php if ($currentUser['role'] === 'admin'): ?>
                    <a href="room/create_room.php" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Thêm phòng mới
                    </a>
                <?php endif; ?>
            </div>

            <!-- Thông báo -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_GET['success']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_GET['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Bảng danh sách phòng -->
            <div class="table-responsive" style="max-height: 500px;">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên phòng</th>
                            <th>Loại phòng</th>
                            <th>Giá (VND)</th>
                            <th>Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require '../handle/room_process.php';
                        $rooms = handleGetAllRooms();

                        foreach ($rooms as $room):
                        ?>
                        <tr>
                            <td><span class="badge bg-secondary"><?= $room["id"] ?></span></td>
                            <td class="fw-semibold"><?= htmlspecialchars($room["name"]) ?></td>
                            <td><?= htmlspecialchars($room["type"]) ?></td>
                            <td><span class="badge bg-info text-dark"><?= number_format($room["price"]) ?> đ</span></td>
                            <td>
                                <?php
                                if ($room["status"] === "available") {
                                    echo '<span class="badge bg-success">Còn trống</span>';
                                } elseif ($room["status"] === "booked") {
                                    echo '<span class="badge bg-warning text-dark">Đã đặt phòng</span>';
                                } elseif ($room["status"] === "occupied") {
                                    echo '<span class="badge bg-primary">Đang ở</span>';
                                } else {
                                    echo '<span class="badge bg-secondary">Bảo trì</span>';
                                }
                                ?>
                            </td>
                      <td class="text-center">
                            <!-- Nút xem chi tiết: ai cũng thấy -->
                            <a href="room/detail_room.php?id=<?= $room["id"] ?>" class="btn btn-info btn-sm action-btn" title="Xem">
                                <i class="bi bi-eye"></i>
                            </a>

                            <?php if ($currentUser['role'] === 'admin'): ?>
                                <!-- Nút sửa + xóa chỉ Admin -->
                                <a href="room/edit_room.php?id=<?= $room["id"] ?>" class="btn btn-warning btn-sm action-btn" title="Sửa">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="../handle/room_process.php?action=delete&id=<?= $room["id"] ?>" 
                                onclick="return confirm('Bạn có chắc chắn muốn xóa phòng này?')" 
                                class="btn btn-danger btn-sm action-btn" title="Xóa">
                                    <i class="bi bi-trash"></i>
                                </a>
                            <?php else: ?>
                                <!-- Nút đặt phòng chỉ hiện với User -->
                                <?php if ($room["status"] === "available"): ?>
                                    <a href="booking/create_booking.php?room_id=<?= $room["id"] ?>" 
                                    class="btn btn-success btn-sm action-btn" title="Đặt phòng">
                                        <i class="bi bi-cart-plus"></i>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>

                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
