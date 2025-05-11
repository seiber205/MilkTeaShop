<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MilkTeaShop</title>
    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- custom css file link -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- header section starts -->
    <header class="header">
        <a href="index.php" class="logo">
            <img src="images/logo.jpg" alt="Logo Trà Sữa TFT">
        </a>
        <nav class="navbar">
            <div class="nav-left">
                <a href="#home">Trang chủ</a>
                <a href="#about">Chúng tôi</a>
                <a href="#menu">Danh sách</a>
                <a href="#products">Đánh giá</a>
            </div>
            <div class="nav-right">
                <?php if (isset($_SESSION['username']) || isset($_COOKIE['username'])): ?>
                    <?php $username = isset($_SESSION['username']) ? $_SESSION['username'] : $_COOKIE['username']; ?>
                    <div class="user-dropdown">
                        <a href="#" class="username">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($username); ?>
                        </a>
                        <div class="dropdown-content">
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin' || isset($_COOKIE['role']) && $_COOKIE['role'] == 'admin'): ?>
                                <a href="quanly/admin_dashboard.php">Quản lý đơn hàng</a>
                            <?php endif; ?>
                            <a href="đặt hàng/order_status.php">Trạng thái đơn hàng</a>
                            <a href="đánh giá/favorites.php">Yêu thích</a>
                            <a href="user/dangxuat.php">Đăng xuất</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="user/dangnhap.php">Đăng nhập</a>
                    <a href="user/dangky.php">Đăng ký</a>
                <?php endif; ?>
            </div>
        </nav>
     
    </header>
    <!-- header section ends -->

    <!-- home section starts -->
    <section class="home" id="home">
        <div class="content">
            <h3>Trà sữa TFT</h3>
            <p>Chào mừng bạn đến với TFT – nơi mỗi ly trà sữa là một chuyến du hành đầy màu sắc và hương vị! Tại TFT, chúng tôi mang đến những ly trà sữa thơm ngon, được chế biến từ những nguyên liệu tươi mới, kết hợp với sự sáng tạo độc đáo. Hãy để mỗi ngụm trà sữa tại TFT là khoảnh khắc thư giãn, ngọt ngào, làm bừng sáng cả ngày của bạn!</p>
            <a href="#menu" class="btn">Đặt ngay</a>
        </div>
    </section>
    <!-- home section ends -->

    <!-- about section starts -->
    <section class="about" id="about">
        <h1 class="heading"> <span>Về</span> chúng tôi </h1>
        <div class="row">
            <div class="image">
                <img src="images/logo2.jpg" alt="">
            </div>
            <div class="content">
                <h3>điều gì khiến chúng tôi đặc biệt?</h3>
                <p>Chúng tôi là một nhóm sinh viên đến từ Đại Học Giao Thông Vận Tải Thành Phố Hồ Chí Minh, với niềm đam mê công nghệ và thiết kế web. Chúng tôi đã cùng nhau xây dựng một website dành riêng cho quán trà sữa TFT, với mục tiêu mang đến cho khách hàng một trải nghiệm dễ dàng, thuận tiện và đầy thú vị.</p>
                <p>Website không chỉ là một công cụ để khách hàng dễ dàng tìm hiểu và đặt món, mà còn là một cách để kết nối yêu thích trà sữa với sự sáng tạo, công nghệ hiện đại. Chúng tôi vô cùng biết ơn và trân trọng sự hỗ trợ của nhà trường, Đại Học Giao Thông Vận Tải TP.HCM, đã tạo điều kiện cho chúng tôi thực hiện dự án này và phát triển những kỹ năng quý giá trong suốt quá trình học tập.</p>
                <a href="#home" class="btn">Tìm hiểu thêm</a>
            </div>
        </div>
    </section>
    <!-- about section ends -->

    <!-- menu section starts -->
    <section class="menu" id="menu">
        <h1 class="heading"> thực đơn <span>của chúng tôi</span> </h1>
        <div class="box-container">
            <div class="box">
                <img src="images/product-1.png" alt="">
                <h3>Trà sữa kem trứng nướng</h3>
                <div class="price">25.000Đ <span>27.000Đ</span></div>
                <a href="đặt hàng/order.php?id=1&item=Trà sữa kem trứng nướng&price=25000" class="btn">Đặt ngay</a>
            </div>
            <div class="box">
                <img src="images/product-3.png" alt="">
                <h3>Trà sữa trân châu đường đen</h3>
                <div class="price">24.000Đ <span>26.000Đ</span></div>
                <a href="đặt hàng/order.php?id=3&item=Trà sữa trân châu đường đen&price=24000" class="btn">Đặt ngay</a>
            </div>
            <div class="box">
                <img src="images/product-6.png" alt="">
                <h3>Sữa tươi trân châu đường đen</h3>
                <div class="price">25.000Đ <span>27.000Đ</span></div>
                <a href="đặt hàng/order.php?id=6&item=Sữa tươi trân châu đường đen&price=25000" class="btn">Đặt ngay</a>
            </div>
            <div class="box">
                <img src="images/product-2.png" alt="">
                <h3>Sữa tươi kem trứng nướng</h3>
                <div class="price">27.000Đ <span>29.000Đ</span></div>
                <a href="đặt hàng/order.php?id=2&item=Sữa tươi kem trứng nướng&price=27000" class="btn">Đặt ngay</a>
            </div>
            <div class="box">
                <img src="images/product-5.png" alt="">
                <h3>Sữa tươi than tre</h3>
                <div class="price">23.000Đ <span>25.000Đ</span></div>
                <a href="đặt hàng/order.php?id=5&item=Sữa tươi than tre&price=23000" class="btn">Đặt ngay</a>
            </div>
            <div class="box">
                <img src="images/product-4.png" alt="">
                <h3>Trà sữa không</h3>
                <div class="price">20.000Đ <span>22.000Đ</span></div>
                <a href="đặt hàng/order.php?id=4&item=Trà sữa không&price=20000" class="btn">Đặt ngay</a>
            </div>
        </div>
    </section>
    <!-- menu section ends -->

    <!-- products section starts -->
    <section class="products" id="products">
        <h1 class="heading"> Các bài <span>đánh giá</span> </h1>
        <div class="box-container">
            <div class="box">
                <div class="icons">
                    <a href="<?php echo (isset($_SESSION['username']) || isset($_COOKIE['username'])) ? 'đặt hàng/order.php?id=1&item=Trà sữa kem trứng nướng&price=25000' : 'user/dangnhap.php'; ?>" class="fas fa-shopping-cart"></a>
                    <a href="<?php echo (isset($_SESSION['username']) || isset($_COOKIE['username'])) ? 'đánh giá/add_to_favorites.php?item=Trà sữa kem trứng nướng&image=images/product-1.png&price=25000' : 'user/dangnhap.php'; ?>" class="fas fa-heart"></a>
                    <a href="<?php echo (isset($_SESSION['username']) || isset($_COOKIE['username'])) ? 'đánh giá/review.php?item=Trà sữa kem trứng nướng&image=images/product-1.png' : 'user/dangnhap.php'; ?>" class="fas fa-eye"></a>
                </div>
                <div class="image">
                    <img src="images/product-1.png" alt="">
                </div>
                <div class="content">
                    <h3>Trà sữa kem trứng nướng</h3>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <div class="price">25.000Đ <span>27.000Đ</span></div>
                </div>
            </div>
            <div class="box">
                <div class="icons">
                    <a href="<?php echo (isset($_SESSION['username']) || isset($_COOKIE['username'])) ? 'đặt hàng/order.php?id=2&item=Sữa tươi kem trứng nướng&price=27000' : 'user/dangnhap.php'; ?>" class="fas fa-shopping-cart"></a>
                    <a href="<?php echo (isset($_SESSION['username']) || isset($_COOKIE['username'])) ? 'đánh giá/add_to_favorites.php?item=Sữa tươi kem trứng nướng&image=images/product-2.png&price=27000' : 'user/dangnhap.php'; ?>" class="fas fa-heart"></a>
                    <a href="<?php echo (isset($_SESSION['username']) || isset($_COOKIE['username'])) ? 'đánh giá/review.php?item=Sữa tươi kem trứng nướng&image=images/product-2.png' : 'user/dangnhap.php'; ?>" class="fas fa-eye"></a>
                </div>
                <div class="image">
                    <img src="images/product-2.png" alt="">
                </div>
                <div class="content">
                    <h3>Sữa tươi kem trứng nướng</h3>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <div class="price">27.000Đ <span>29.000Đ</span></div>
                </div>
            </div>
            <div class="box">
                <div class="icons">
                    <a href="<?php echo (isset($_SESSION['username']) || isset($_COOKIE['username'])) ? 'đặt hàng/order.php?id=3&item=Trà sữa trân châu đường đen&price=24000' : 'user/dangnhap.php'; ?>" class="fas fa-shopping-cart"></a>
                    <a href="<?php echo (isset($_SESSION['username']) || isset($_COOKIE['username'])) ? 'đánh giá/add_to_favorites.php?item=Trà sữa trân châu đường đen&image=images/product-3.png&price=24000' : 'user/dangnhap.php'; ?>" class="fas fa-heart"></a>
                    <a href="<?php echo (isset($_SESSION['username']) || isset($_COOKIE['username'])) ? 'đánh giá/review.php?item=Trà sữa trân châu đường đen&image=images/product-3.png' : 'user/dangnhap.php'; ?>" class="fas fa-eye"></a>
                </div>
                <div class="image">
                    <img src="images/product-3.png" alt="">
                </div>
                <div class="content">
                    <h3>Trà sữa trân châu đường đen</h3>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <div class="price">24.000Đ <span>26.000Đ</span></div>
                </div>
            </div>
        </div>
    </section>
    <!-- products section ends -->

    <!-- custom js file link -->
    <script src=""></script>
</body>
</html>