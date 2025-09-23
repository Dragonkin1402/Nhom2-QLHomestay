<?php
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');
require_once __DIR__ . '/../functions/payment_functions.php';

// Lấy thông tin user đang đăng nhập
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

// Nếu admin => lấy tất cả, nếu user => chỉ lấy payment của mình
if ($role === 'admin') {
    $payments = getAllPayments();
} else {
    $payments = getPaymentsByUser($user_id); // hàm này bạn thêm trong payment_functions.php
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Homestay - Danh sách Payment</title>
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
                    <i class="bi bi-credit-card-2-front-fill"></i> Danh sách thanh toán
                </h3>
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
                        <th>Booking ID</th>
                        <th>Số tiền</th>
                        <th>Ngày thanh toán</th>
                        <th>Phương thức</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><span class="badge bg-secondary"><?= $payment['id'] ?></span></td>
                            <td><?= htmlspecialchars($payment['booking_id']) ?></td>
                            <td><span class="badge bg-info text-dark"><?= number_format($payment['amount']) ?> đ</span></td>
                            <td><?= htmlspecialchars($payment['date']) ?></td>
                            <td>
                                <?php
                                switch ($payment['method']) {
                                    case 'cash':
                                        echo '<span class="badge bg-success">Tiền mặt</span>';
                                        break;
                                    case 'bank_transfer':
                                        echo '<span class="badge bg-primary">Chuyển khoản</span>';
                                        break;
                                    case 'credit_card':
                                        echo '<span class="badge bg-warning text-dark">Thẻ tín dụng</span>';
                                        break;
                                    default:
                                        echo '<span class="badge bg-secondary">Khác</span>';
                                        break;
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <?php if ($role === 'admin'): ?>
                                    <a href="payment/edit_payment.php?id=<?= $payment['id'] ?>"
                                       class="btn btn-sm btn-warning action-btn" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="../handle/payment_process.php?action=delete&id=<?= $payment['id'] ?>"
                                       onclick="return confirm('Bạn có chắc muốn xóa payment này?')"
                                       class="btn btn-sm btn-danger action-btn" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">---</span>
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

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
