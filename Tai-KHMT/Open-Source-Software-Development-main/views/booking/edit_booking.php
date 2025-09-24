<?php
require_once __DIR__ . '/../../functions/auth.php';
checkLogin(__DIR__ . '/../../index.php');
require_once __DIR__ . '/../../functions/booking_functions.php';
require_once __DIR__ . '/../../functions/customer_functions.php';
require_once __DIR__ . '/../../functions/room_functions.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Chỉnh sửa đơn đặt phòng - Homestay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-3">
        <h3 class="mt-3 mb-4 text-center">CHỈNH SỬA ĐƠN ĐẶT PHÒNG</h3>
        <?php
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                header("Location: ../booking.php?error=Không tìm thấy đơn đặt phòng");
                exit;
            }

            $id = $_GET['id'];

            require_once __DIR__ . '/../../handle/booking_process.php';
            $bookingInfo = handleGetBookingById($id);

            if (!$bookingInfo) {
                header("Location: ../booking.php?error=Không tìm thấy đơn đặt phòng");
                exit;
            }

            $customers = getAllCustomersForDropdown();
            $rooms = getAllRoomsForDropdown();

            if (isset($_GET['error'])) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
                    . htmlspecialchars($_GET['error'])
                    . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>'
                    . '</div>';
            }
        ?>

        <script>
        setTimeout(() => {
            let alertNode = document.querySelector('.alert');
            if (alertNode) {
                try {
                    let bsAlert = bootstrap.Alert.getOrCreateInstance(alertNode);
                    bsAlert.close();
                } catch (e) {}
            }
        }, 3000);
        </script>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form action="../../handle/booking_process.php" method="POST">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($bookingInfo['id'] ?? '') ?>">

                            <!-- Chọn khách hàng -->
                            <div class="mb-3">
                                <label for="customer_id" class="form-label">Khách hàng</label>
                                <select class="form-select" id="customer_id" name="customer_id" required>
                                    <option value="">-- Chọn khách hàng --</option>
                                    <?php foreach ($customers as $cus): ?>
                                        <option value="<?= htmlspecialchars($cus['id']) ?>" <?= ($cus['id'] == ($bookingInfo['customer_id'] ?? '')) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cus['name']) ?> (<?= htmlspecialchars($cus['email']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Chọn phòng -->
                            <div class="mb-3">
                                <label for="room_id" class="form-label">Phòng</label>
                                <select class="form-select" id="room_id" name="room_id" required>
                                    <option value="">-- Chọn phòng --</option>
                                    <?php foreach ($rooms as $room): ?>
                                        <option value="<?= htmlspecialchars($room['id']) ?>" <?= ($room['id'] == ($bookingInfo['room_id'] ?? '')) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($room['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Ngày nhận/trả phòng -->
                            <div class="mb-3">
                                <label for="checkin" class="form-label">Ngày nhận phòng</label>
                                <input type="date" class="form-control" id="checkin" name="checkin"
                                       value="<?= htmlspecialchars($bookingInfo['checkin'] ?? '') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="checkout" class="form-label">Ngày trả phòng</label>
                                <input type="date" class="form-control" id="checkout" name="checkout"
                                       value="<?= htmlspecialchars($bookingInfo['checkout'] ?? '') ?>" required>
                            </div>

                            <!-- Chọn trạng thái -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Trạng thái</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending"   <?= ($bookingInfo['status']=='pending')?'selected':'' ?>>Chờ xác nhận</option>
                                    <option value="confirmed" <?= ($bookingInfo['status']=='confirmed')?'selected':'' ?>>Đã xác nhận</option>
                                    <option value="cancelled" <?= ($bookingInfo['status']=='cancelled')?'selected':'' ?>>Đã hủy</option>
                                    <option value="completed" <?= ($bookingInfo['status']=='completed')?'selected':'' ?>>Hoàn tất</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="../booking.php" class="btn btn-secondary me-md-2">Hủy</a>
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
