<?php
require_once __DIR__ . '/../functions/payment_functions.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'create') {
    $booking_id = $_POST['booking_id'];
    $amount     = $_POST['amount'];
    $method     = $_POST['method'];
    $date       = $_POST['date'];

    if (addPayment($booking_id, $amount, $method, $date)) {
        header("Location: ../views/payment.php?success=Thêm payment thành công");
    } else {
        header("Location: ../views/payment/create_payment.php?error=Thêm payment thất bại");
    }
    exit;
}

if ($action === 'edit') {
    $id         = $_POST['id'];
    $booking_id = $_POST['booking_id'];
    $amount     = $_POST['amount'];
    $method     = $_POST['method'];
    $date       = $_POST['date'];

    if (updatePayment($id, $booking_id, $amount, $method, $date)) {
        header("Location: ../views/payment.php?success=Cập nhật payment thành công");
    } else {
        header("Location: ../views/payment/edit_payment.php?id=$id&error=Cập nhật payment thất bại");
    }
    exit;
}

if ($action === 'delete') {
    $id = $_GET['id'] ?? null;
    if ($id && deletePayment($id)) {
        header("Location: ../views/payment.php?success=Xóa payment thành công");
    } else {
        header("Location: ../views/payment.php?error=Xóa payment thất bại");
    }
    exit;
}
