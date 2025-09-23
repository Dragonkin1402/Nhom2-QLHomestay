<?php
require_once __DIR__ . '/../functions/auth.php';
checkLogin(__DIR__ . '/../index.php');
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Danh sách khách hàng - Homestay</title>
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
                        <i class="bi bi-people-fill"></i> Danh sách khách hàng
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
                                <th>Tên khách hàng</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once '../functions/customer_functions.php';
                            $customers = getAllCustomers();

                            foreach ($customers as $cus): ?>
                                <tr>
                                    <td><span class="badge bg-secondary"><?= $cus["id"] ?></span></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($cus["name"]) ?></td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            <?= htmlspecialchars($cus["email"]) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            <?= htmlspecialchars($cus["phone"]) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="customer/edit_customer.php?id=<?= $cus["id"] ?>" 
                                           class="btn btn-sm btn-warning action-btn" title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="../handle/customer_process.php?action=delete&id=<?= $cus["id"] ?>" 
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này?')" 
                                           class="btn btn-sm btn-danger action-btn" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </a>
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
