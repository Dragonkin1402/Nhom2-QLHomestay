<?php
require_once __DIR__ . '/../../functions/auth.php';
checkLogin(__DIR__ . '/../../index.php');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Homestay - Chỉnh sửa phòng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-3">
        <h3 class="mt-3 mb-4 text-center">CHỈNH SỬA PHÒNG</h3>
        <?php
            // Kiểm tra có ID không
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                header("Location: ../room.php?error=Không tìm thấy phòng");
                exit;
            }
            
            $id = $_GET['id'];
            
            // Lấy thông tin phòng
            require_once __DIR__ . '/../../handle/room_process.php';
            $room = handleGetRoomById($id);

            if (!$room) {
                header("Location: ../room.php?error=Không tìm thấy phòng");
                exit;
            }
            
            // Hiển thị thông báo lỗi
            if (isset($_GET['error'])) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ' . htmlspecialchars($_GET['error']) . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>';
            }
        ?>
            <script>
            // Sau 3 giây sẽ tự động ẩn alert
            setTimeout(() => {
                let alertNode = document.querySelector('.alert');
                if (alertNode) {
                    let bsAlert = bootstrap.Alert.getOrCreateInstance(alertNode);
                    bsAlert.close();
                }
            }, 3000);
            </script>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="../../handle/room_process.php" method="POST">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($room['id']); ?>">

                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên phòng</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="<?php echo htmlspecialchars($room['name']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="type" class="form-label">Loại phòng</label>
                                    <input type="text" class="form-control" id="type" name="type"
                                        value="<?php echo htmlspecialchars($room['type']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá (VND)</label>
                                    <input type="number" class="form-control" id="price" name="price"
                                        value="<?php echo htmlspecialchars($room['price']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="available" <?php echo ($room['status'] === 'available') ? 'selected' : ''; ?>>Còn trống</option>
                                        <option value="booked" <?php echo ($room['status'] === 'booked') ? 'selected' : ''; ?>>Đã đặt</option>
                                        <option value="maintenance" <?php echo ($room['status'] === 'maintenance') ? 'selected' : ''; ?>>Bảo trì</option>
                                    </select>
                                </div>

                                <!-- Mô tả -->
                                <div class="mb-3">
                                    <label for="description" class="form-label">Mô tả</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($room['description'] ?? ''); ?></textarea>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="../room.php" class="btn btn-secondary me-md-2">Hủy</a>
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
