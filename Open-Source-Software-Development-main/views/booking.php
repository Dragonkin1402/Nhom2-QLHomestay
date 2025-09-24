<?php
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');
require_once __DIR__ . '/../functions/booking_functions.php';

// Lấy role + user_id từ session
$role = $_SESSION['role'] ?? 'user';
$user_id = $_SESSION['user_id'] ?? null;

// Lấy danh sách booking phù hợp role
$bookings = getAllBookings($role, $user_id);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Homestay - Danh sách Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .card {
            border-radius: 15px;
        }
        .table thead th {
            position: sticky;
            top: 0;
            background-color: #0d6efd;
            color: white;
            z-index: 10;
        }
        .action-btn {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        .action-btn i {
            font-size: 1rem;
        }
    </style>
</head>

<body>
<?php include './menu.php'; ?>

<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Tiêu đề -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-primary m-0">
                    <i class="bi bi-journal-check"></i> Danh sách Booking
                </h3>
                <?php if ($role === 'admin'): ?>
                <!-- Admin có thể tạo booking -->
                 <!--
                <a href="booking/create_booking.php" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Tạo Booking
                </a>
                -->
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

            <!-- Bảng -->
            <div class="table-responsive" style="max-height: 500px;">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Phòng</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Trạng thái</th>
                            <?php if ($role === 'admin'): ?>
                            <th class="text-center">Thao tác</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bookings)): ?>
                            <tr>
                                <td colspan="<?= $role === 'admin' ? 7 : 6 ?>" class="text-center text-muted">
                                    Không có booking nào
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td><span class="badge bg-secondary"><?= $booking["id"] ?></span></td>
                                    <td><?= htmlspecialchars($booking["customer_name"]) ?></td>
                                    <td><?= htmlspecialchars($booking["room_name"]) ?></td>
                                    <td><?= htmlspecialchars($booking["checkin"]) ?></td>
                                    <td><?= htmlspecialchars($booking["checkout"]) ?></td>
                                    <td>
                                        <?php
                                        switch ($booking["status"]) {
                                            case 'pending':
                                                echo '<span class="badge bg-warning text-dark">Chờ xác nhận</span>';
                                                break;
                                            case 'confirmed':
                                                echo '<span class="badge bg-success">Đã xác nhận</span>';
                                                break;
                                            case 'cancelled':
                                                echo '<span class="badge bg-danger">Đã hủy</span>';
                                                break;
                                            case 'completed':
                                                echo '<span class="badge bg-primary">Hoàn tất</span>';
                                                break;
                                            default:
                                                echo '<span class="badge bg-secondary">Khác</span>';
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <?php if ($role === 'admin'): ?>
                                    <td class="text-center">
                                        <a href="booking/edit_booking.php?id=<?= $booking["id"] ?>"
                                           class="btn btn-sm btn-warning action-btn" title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="../handle/booking_process.php?action=delete&id=<?= $booking["id"] ?>"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa booking này?')"
                                           class="btn btn-sm btn-danger action-btn" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Tự động ẩn alert sau 3 giây
    setTimeout(() => {
        let alertNode = document.querySelector('.alert');
        if (alertNode) {
            let bsAlert = bootstrap.Alert.getOrCreateInstance(alertNode);
            bsAlert.close();
        }
    }, 3000);
</script>
</body>
</html>
