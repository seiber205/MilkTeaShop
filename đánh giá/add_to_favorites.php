<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../user/dangnhap.php");
    exit();
}

if (isset($_GET['item']) && isset($_GET['image']) && isset($_GET['price'])) {
    $item = $_GET['item'];
    $image = $_GET['image'];
    $price = $_GET['price'];

    // Khởi tạo mảng yêu thích nếu chưa có
    if (!isset($_SESSION['favorites'])) {
        $_SESSION['favorites'] = [];
    }

    // Kiểm tra xem sản phẩm đã có trong danh sách yêu thích chưa
    $alreadyExists = false;
    foreach ($_SESSION['favorites'] as $favorite) {
        if ($favorite['item'] === $item) {
            $alreadyExists = true;
            break;
        }
    }

    // Nếu chưa có, thêm vào danh sách yêu thích
    if (!$alreadyExists) {
        $_SESSION['favorites'][] = [
            'item' => $item,
            'image' => $image,
            'price' => $price
        ];
    }
}

// Chuyển hướng trở lại trang chủ
header("Location: ../index.php#products");
exit();
?>