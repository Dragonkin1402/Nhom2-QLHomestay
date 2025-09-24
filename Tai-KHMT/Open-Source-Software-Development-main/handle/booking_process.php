<?php
require_once __DIR__ . '/../functions/booking_functions.php';

$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} elseif (isset($_POST['action'])) {
    $action = $_POST['action'];
}

switch ($action) {
    case 'create':
        handleCreateBooking();
        break;
    case 'edit':
        handleEditBooking();
        break;
    case 'delete':
        handleDeleteBooking();
        break;
}

function handleGetAllBookings() {
    return getAllBookings();
}

function handleGetBookingById($id) {
    return getBookingById($id);
}

function handleCreateBooking() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/booking/create_booking.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (empty($_POST['customer_id']) || empty($_POST['room_id']) || empty($_POST['checkin']) || empty($_POST['checkout'])) {
        header("Location: ../views/booking/create_booking.php?error=Thiếu thông tin cần thiết");
        exit();
    }

    $customer_id = intval($_POST['customer_id']);
    $room_id = intval($_POST['room_id']);
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];

    // ✅ addBooking phải trả về booking_id vừa tạo
    $result = addBooking($customer_id, $room_id, $checkin, $checkout);

    if ($result) {
        $booking_id = $result;

        // ✅ CHUYỂN SANG TRANG TẠO PAYMENT luôn, kèm booking_id
        header("Location: ../views/payment/create_payment.php?booking_id=" . $booking_id);
    } else {
        header("Location: ../views/booking/create_booking.php?error=Không thể tạo booking");
    }
    exit();
}

function handleEditBooking() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/booking.php?error=Phương thức không hợp lệ");
        exit();
    }

    // ✅ sửa lại: trước bạn copy-paste sai, toàn $_POST['id']
    if (!isset($_POST['id']) || !isset($_POST['customer_id']) || !isset($_POST['room_id']) 
        || !isset($_POST['checkin']) || !isset($_POST['checkout']) || !isset($_POST['status'])) {
        header("Location: ../views/booking.php?error=Thiếu thông tin cần thiết");
        exit();
    }

    $id = intval($_POST['id']);
    $customer_id = intval($_POST['customer_id']);
    $room_id = intval($_POST['room_id']);
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $status = trim($_POST['status']);

    if (empty($customer_id) || empty($room_id) || empty($checkin) || empty($checkout) || empty($status)) {
        header("Location: ../views/booking/edit_booking.php?id=" . $id . "&error=Vui lòng điền đầy đủ thông tin");
        exit();
    }

    $result = updateBooking($id, $customer_id, $room_id, $checkin, $checkout, $status);

    if ($result) {
        header("Location: ../views/booking.php?success=Cập nhật đặt phòng thành công");
    } else {
        header("Location: ../views/booking/edit_booking.php?id=" . $id . "&error=Cập nhật đặt phòng thất bại");
    }
    exit();
}

function handleDeleteBooking() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header("Location: ../views/booking.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        header("Location: ../views/booking.php?error=Không tìm thấy ID đặt phòng");
        exit();
    }

    $id = $_GET['id'];

    if (!is_numeric($id)) {
        header("Location: ../views/booking.php?error=ID đặt phòng không hợp lệ");
        exit();
    }

    $result = deleteBooking($id);

    if ($result) {
        header("Location: ../views/booking.php?success=Xóa đặt phòng thành công");
    } else {
        header("Location: ../views/booking.php?error=Xóa đặt phòng thất bại");
    }
    exit();
}
?>
