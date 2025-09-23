<?php
// create_booking.php (sửa để tránh lỗi null access)
require_once __DIR__ . '/../../functions/auth.php';
checkLogin(__DIR__ . '/../../index.php');

require_once __DIR__ . '/../../functions/booking_functions.php';
require_once __DIR__ . '/../../functions/customer_functions.php';
require_once __DIR__ . '/../../functions/room_functions.php';

// Lấy user hiện tại (dùng hàm trong auth để đồng bộ)
$currentUser = function_exists('getCurrentUser') ? getCurrentUser() : ($_SESSION['user'] ?? null);

// Nếu không có user (dù checkLogin đã có) thì redirect
if (!$currentUser) {
    header('Location: ../../index.php');
    exit;
}

// Lấy room_id từ URL (nếu có) và ép kiểu int
$roomId = isset($_GET['room_id']) ? intval($_GET['room_id']) : 0;
$room = $roomId ? getRoomById($roomId) : null;

// Lấy danh sách khách hàng + phòng (dùng cho Admin)
$customers = getAllCustomersForDropdown();
$rooms = getAllRoomsForDropdown();

// Chuẩn hóa role về chữ thường để so sánh an toàn
$role = strtolower($currentUser['role'] ?? 'user');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt phòng - Homestay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <h3 class="mt-3 mb-4 text-primary">ĐẶT PHÒNG</h3>

            <!-- Hiển thị thông báo lỗi nếu có -->
            <?php if (!empty($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_GET['error']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Nếu user là user (không phải admin) nhưng chưa truyền room_id thì báo và chuyển hướng -->
            <?php if ($role === 'user' && !$room): ?>
                <div class="alert alert-warning">
                    Bạn chưa chọn phòng để đặt. Vui lòng quay về <a href="../room.php">Danh sách phòng</a> và chọn phòng.
                </div>
            <?php endif; ?>

            <form action="../../handle/booking_process.php" method="POST">
                <input type="hidden" name="action" value="create">

                <?php if ($role === 'admin'): ?>
                    <!-- Admin: chọn khách hàng & phòng -->
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Khách hàng</label>
                        <select class="form-select" id="customer_id" name="customer_id" required>
                            <option value="">-- Chọn khách hàng --</option>
                            <?php foreach ($customers as $cus): ?>
                                <option value="<?= htmlspecialchars($cus['id']) ?>">
                                    <?= htmlspecialchars($cus['name']) ?>
                                    (<?= !empty($cus['email']) ? htmlspecialchars($cus['email']) : 'Không có email' ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="room_id" class="form-label">Phòng</label>
                        <select class="form-select" id="room_id" name="room_id" required>
                            <option value="">-- Chọn phòng --</option>
                            <?php foreach ($rooms as $r): ?>
                                <option value="<?= htmlspecialchars($r['id']) ?>">
                                    <?= htmlspecialchars($r['name']) ?>
                                    <?= !empty($r['type']) ? ' - ' . htmlspecialchars($r['type']) : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                <?php else: ?>
                    <!-- User: tự động fill khách hàng & phòng (phải có room) -->
                    <input type="hidden" name="customer_id" value="<?= htmlspecialchars($currentUser['id']) ?>">
                    <input type="hidden" name="room_id" value="<?= htmlspecialchars($room['id'] ?? '') ?>">

                    <div class="mb-3">
                        <label class="form-label">Khách hàng</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($currentUser['username'] ?? $currentUser['name'] ?? 'User') ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phòng</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($room['name'] ?? 'Chưa chọn phòng') ?>" readonly>
                    </div>
                <?php endif; ?>

                <!-- Check-in/out: dùng chung cho cả Admin & User -->
                <div class="mb-3">
                    <label for="checkin" class="form-label">Ngày nhận phòng</label>
                    <input type="date" class="form-control" id="checkin" name="checkin" required>
                </div>

                <div class="mb-3">
                    <label for="checkout" class="form-label">Ngày trả phòng</label>
                    <input type="date" class="form-control" id="checkout" name="checkout" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">Xác nhận đặt phòng</button>
                    <a class="btn btn-secondary" href="<?= $role === 'admin' ? '../booking.php' : '../room.php' ?>">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
