<?php
require_once __DIR__ . '/../../functions/auth.php';
checkLogin(__DIR__ . '/../../index.php');
require_once __DIR__ . '/../../functions/payment_functions.php';
require_once __DIR__ . '/../../functions/booking_functions.php';

$id = $_GET['id'] ?? null;
$payment = [];
if ($id) {
    $payment = getPaymentById($id) ?? [];
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: ../payment.php?error=Không tìm thấy payment");
    exit;
}
$payment = getPaymentById($id);
$bookings = getAllBookings();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Chỉnh sửa Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-3">
    <h3>CHỈNH SỬA PAYMENT</h3>

    <form action="../../handle/payment_process.php" method="POST">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" value="<?= $payment['id'] ?>">

        <div class="mb-3">
            <label for="booking_id" class="form-label">Booking</label>
                <select name="booking_id" class="form-select">
                    <?php foreach ($bookings as $b): ?>
                        <option value="<?= $b['id'] ?>"
                            <?= (isset($payment['booking_id']) && $b['id'] == $payment['booking_id']) ? 'selected' : '' ?>>
                            Booking #<?= $b['id'] ?> - <?= htmlspecialchars($b['customer_name'] ?? 'N/A') ?>
                        </option>
                    <?php endforeach; ?>
                </select>

        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Số tiền</label>
            <input type="number" step="0.01" class="form-control" id="amount" name="amount"
                   value="<?= htmlspecialchars($payment['amount']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Ngày thanh toán</label>
            <input type="datetime-local" class="form-control" id="date" name="date"
                   value="<?= date('Y-m-d\TH:i', strtotime($payment['date'])) ?>" required>
        </div>

        <div class="mb-3">
            <label for="method" class="form-label">Phương thức</label>
            <select class="form-select" id="method" name="method" required>
                <option value="cash" <?= (isset($payment['method']) && $payment['method']=='cash') ? 'selected' : '' ?>>Tiền mặt</option>
                <option value="bank_transfer" <?= (isset($payment['method']) && $payment['method']=='bank_transfer') ? 'selected' : '' ?>>Chuyển khoản</option>
                <option value="credit_card" <?= (isset($payment['method']) && $payment['method']=='credit_card') ? 'selected' : '' ?>>Thẻ tín dụng</option>
                <option value="online" <?= (isset($payment['method']) && $payment['method']=='online') ? 'selected' : '' ?>>Online</option>
            </select>

        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="../payment.php" class="btn btn-secondary">Hủy</a>
    </form>
</div>
</body>
</html>
