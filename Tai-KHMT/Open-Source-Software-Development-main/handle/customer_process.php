<?php
require_once __DIR__ . '/../functions/customer_functions.php';

// Kiểm tra action được truyền qua URL hoặc POST
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

switch ($action) {
    case 'create':
        handleCreateCustomer();
        break;
    case 'edit':
        handleEditCustomer();
        break;
    case 'delete':
        handleDeleteCustomer();
        break;
}

/**
 * Lấy tất cả danh sách khách hàng
 */
function handleGetAllCustomers() {
    return getAllCustomers();
}

/**
 * Lấy thông tin khách hàng theo ID
 */
function handleGetCustomerById($id) {
    return getCustomerById($id);
}

/**
 * Xử lý tạo khách hàng mới
 */
function handleCreateCustomer() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/customer.php?error=Phương thức không hợp lệ");
        exit();
    }

    if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['phone'])) {

        header("Location: ../views/customer/create_customer.php?error=Thiếu thông tin cần thiết");
        exit();
    }

     $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);


    if (empty($name) || empty($email) || empty($phone)) {
        header("Location: ../views/customer/create_customer.php?error=Vui lòng điền đầy đủ thông tin");
        exit();
    }

    $result = addCustomer($name, $email, $phone);

    if ($result) {
        header("Location: ../views/customer.php?success=Thêm khách hàng thành công");
    } else {
        header("Location: ../views/customer/create_customer.php?error=Có lỗi xảy ra khi thêm khách hàng");
    }
    
    exit();
}



/**
 * Xử lý chỉnh sửa khách hàng
 */
function handleEditCustomer() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../views/customer.php?error=Phương thức không hợp lệ");
        exit();
    }
    
    $id = intval($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');


    if ($id <= 0 || empty($name) || empty($email) || empty($phone)) {
        header("Location: ../views/customer/edit_customer.php?id=$id&error=Vui lòng điền đầy đủ thông tin");
        exit();
    }
    
    $result = updateCustomer($id, $name, $email, $phone);
    
    if ($result) {
        header("Location: ../views/customer.php?success=Cập nhật khách hàng thành công");
    } else {
        header("Location: ../views/customer/edit_customer.php?id=$id&error=Cập nhật khách hàng thất bại");
    }
    exit();
}

/**
 * Xử lý xóa khách hàng
 */
function handleDeleteCustomer() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        header("Location: ../views/customers.php?error=Phương thức không hợp lệ");
        exit();
    }
    
    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
        header("Location: ../views/customers.php?error=ID khách hàng không hợp lệ");
        exit();
    }
    
    $result = deleteCustomer($id);
    
    if ($result) {
        header("Location: ../views/customer.php?success=Xóa khách hàng thành công");
    } else {
        header("Location: ../views/customer.php?error=Xóa khách hàng thất bại");
    }
    exit();
}
if ($action === 'delete') {
    $id = $_GET['id'] ?? null;

    if ($id) {
        require_once '../functions/room_functions.php';
        require_once '../functions/booking_functions.php';

        // Lấy tất cả booking của customer trước khi bị xóa (do ON DELETE CASCADE)
        $bookings = getBookingsByCustomerId($id);

        foreach ($bookings as $booking) {
            // Trả phòng về available
            updateRoomStatus($booking['room_id'], 'available');
        }

        // Xóa customer (booking & payment sẽ bị xóa nhờ ràng buộc CSDL)
        if (deleteCustomer($id)) {
            header("Location: ../views/customer.php?success=Xóa khách hàng thành công và phòng đã được giải phóng");
            exit;
        }
    }

    header("Location: ../views/customer.php?error=Xóa khách hàng thất bại");
    exit;
}

