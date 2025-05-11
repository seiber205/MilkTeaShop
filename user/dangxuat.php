<?php
session_start();

// Xóa session (nếu có)
session_unset();
session_destroy();

// Xóa cookies
setcookie('username', '', time() - 3600, "/"); // Đặt thời gian hết hạn trong quá khứ
setcookie('role', '', time() - 3600, "/");

// Chuyển hướng về trang chủ (giao diện khách)
header("Location: ../index.php");
exit();