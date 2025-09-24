<?php
require_once __DIR__ . '/../../functions/auth.php';
checkLogin(__DIR__ . '/../../index.php');
require_once __DIR__ . '/../../functions/booking_functions.php';
require_once __DIR__ . '/../../functions/room_functions.php';

// lấy danh sách booking cho dropdown (mỗi booking phải kèm room_price là số thô)
$bookings = getAllBookingsWithRoom(); // đảm bảo hàm này trả room_price là số (ví dụ 120000)

// Nếu redirect từ booking mới tạo sẽ có ?booking_id=...
$pre_booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;

// ==== cấu hình ngân hàng (thay bằng thông tin homestay thật) ====
$BANK_ID = '970422';        // mã ngân hàng (ví dụ vietcombank mã ở đây là ví dụ)
$ACCOUNT_NO = '1234567890'; // số tài khoản nhận tiền
$ACCOUNT_NAME = 'NGUYEN VAN A'; // tên chủ tài khoản (in hoa, không dấu tốt hơn)
// ==============================================================

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thêm Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #qr_container img { max-width: 320px; }
    </style>
</head>
<body>
<div class="container mt-3">
    <h3>THÊM PAYMENT</h3>

    <form action="../../handle/payment_process.php" method="POST" id="paymentForm">
        <input type="hidden" name="action" value="create">

        <div class="mb-3">
            <label for="booking_id" class="form-label">Booking</label>
            <select class="form-select" id="booking_id" name="booking_id" required onchange="updateAmount()">
                <option value="">-- Chọn booking --</option>
                <?php foreach ($bookings as $b): 
                    // đảm bảo room_price là số thô, không format với dấu phẩy
                    $room_price = isset($b['room_price']) ? $b['room_price'] : 0;
                    $selected = ($pre_booking_id && $pre_booking_id == $b['id']) ? 'selected' : '';
                ?>
                    <option value="<?= htmlspecialchars($b['id']) ?>"
                            data-price="<?= htmlspecialchars($room_price) ?>"
                            data-customer="<?= htmlspecialchars($b['customer_name']) ?>"
                            <?= $selected ?>>
                        Booking #<?= htmlspecialchars($b['id']) ?> - <?= htmlspecialchars($b['customer_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Số tiền (VND)</label>
            <input type="number" step="0.01" class="form-control" id="amount" name="amount" readonly required>
        </div>

       

        <div id="qr_container" class="mb-3" style="display:none;">
            <label class="form-label">QR Thanh toán (Scan bằng app ngân hàng/QR)</label><br>
            <img id="qr_img" src="" alt="QR code">
            <div class="form-text">Quét QR để tự động điền số tài khoản + số tiền + nội dung chuyển khoản.</div>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Ngày thanh toán</label>
            <input type="datetime-local" class="form-control" id="date" name="date" required>
        </div>

        <div class="mb-3">
            <label for="method" class="form-label">Phương thức</label>
            <select class="form-select" id="method" name="method" required>
                <option value="bank_transfer">Chuyển khoản</option>
                <option value="cash">Tiền mặt</option>
          
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Xác nhận thanh toán</button>
        <a href="../payment.php" class="btn btn-secondary">Hủy</a>
    </form>
</div>

<script>
function updateAmount() {
    const select = document.getElementById("booking_id");
    const option = select.options[select.selectedIndex];
    const priceRaw = option ? option.getAttribute("data-price") : "";
    const amountInput = document.getElementById("amount");
    const qrContainer = document.getElementById("qr_container");
    const qrImg = document.getElementById("qr_img");

    if (!option || !option.value) {
        amountInput.value = "";
        qrContainer.style.display = "none";
        qrImg.src = "";
        return;
    }

    const price = parseFloat(priceRaw);
    amountInput.value = isNaN(price) ? "" : price.toFixed(0);

    // Thông tin ngân hàng homestay
    const bankId = "970422"; // mã ngân hàng
    const accountNo = "0972450964"; // số tài khoản
    const accountName = "PHUNG HUU TAI"; // tên chủ tài khoản

    const bookingId = option.value;
    const addInfo = `Thanh toan booking ${bookingId}`;
    const qrUrl = `https://img.vietqr.io/image/${bankId}-${accountNo}-compact2.png?amount=${price}&addInfo=${encodeURIComponent(addInfo)}&accountName=${encodeURIComponent(accountName)}`;

    qrImg.src = qrUrl;
    qrContainer.style.display = "block";
}

// 👉 Gọi lại khi trang vừa load (trường hợp có booking_id được chọn sẵn)
window.addEventListener("DOMContentLoaded", () => {
    updateAmount();
});
</script>

</body>
</html>
