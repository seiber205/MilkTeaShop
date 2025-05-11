<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../user/dangnhap.php");
    exit();
}

$favorites = isset($_SESSION['favorites']) ? $_SESSION['favorites'] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách yêu thích - MilkTeaShop</title>
    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- custom css file link -->
    <link rel="stylesheet" href="favorites.css"> <!-- Chỉ sử dụng favorites.css -->
</head>
<body>
    <!-- header section starts -->
    <header class="header">
        <a href="../index.php" class="logo">
            <img src="../images/logo.jpg" alt="Logo Trà Sữa TFT">
        </a>
        <nav class="navbar">
            <div class="nav-left">
                <a href="../index.php#home">Trang chủ</a>
                <a href="../index.php#about">Chúng tôi</a>
                <a href="../index.php#menu">Danh sách</a>
                <a href="../index.php#products">Sản phẩm</a>
            </div>
            <div class="nav-right">
                <?php
                if (isset($_SESSION['username'])) {
                    echo '<div class="user-dropdown">';
                    echo '<a href="#" class="username"><i class="fas fa-user"></i> ' . htmlspecialchars($_SESSION['username']) . '</a>';
                    echo '<div class="dropdown-content">';
                    echo '<a href="favorites.php">Yêu thích</a>';
                    echo '<a href="../user/dangxuat.php">Đăng xuất</a>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo '<a href="../user/dangnhap.php">Đăng nhập</a>';
                    echo '<a href="../user/dangky.php">Đăng ký</a>';
                }
                ?>
            </div>
        </nav>
      
    </header>
    <!-- header section ends -->

    <!-- favorites section starts -->
    <section class="favorites-section">
        <h1 class="heading"> Danh sách <span>yêu thích</span> </h1>
        <div class="box-container">
            <?php if (count($favorites) > 0) { ?>
                <?php foreach ($favorites as $favorite) { ?>
                    <div class="box">
                        <div class="image">
                            <img src="../<?php echo htmlspecialchars($favorite['image']); ?>" alt="">
                        </div>
                        <div class="content">
                            <h3><?php echo htmlspecialchars($favorite['item']); ?></h3>
                            <div class="price"><?php echo number_format($favorite['price']); ?>Đ</div>
                            <a href="../đặt hàng/order.php?item=<?php echo urlencode($favorite['item']); ?>&price=<?php echo $favorite['price']; ?>" class="btn">Đặt ngay</a>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p class="empty">Bạn chưa có sản phẩm nào trong danh sách yêu thích!</p>
            <?php } ?>
        </div>
    </section>
    <!-- favorites section ends -->

    <!-- custom js file link -->
    <script src=""></script>
</body>
</html>