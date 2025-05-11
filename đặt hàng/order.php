<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Hàng - Trà Sữa TFT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../user/dangnhapvaky.css">
    <style>
        .review-section {
            padding: 9rem 7%;
            background: var(--bg);
            min-height: 100vh;
        }
        .review-section .heading {
            text-align: center;
            color: #fff;
            text-transform: uppercase;
            padding-bottom: 3.5rem;
            font-size: 4rem;
        }
        .review-section .heading span {
            color: var(--main-color);
            text-transform: uppercase;
        }
        .review-section .row {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            background: var(--black);
            padding: 2rem;
            border-radius: 1rem;
        }
        .review-section .row .image {
            flex: 1 1 30rem;
            text-align: center;
        }
        .review-section .row .image img {
            max-width: 100%;
            height: auto;
            border-radius: 1rem;
        }
        .review-section .row .content {
            flex: 1 1 40rem;
        }
        .review-section .row .content h3 {
            font-size: 3rem;
            color: #fff;
            margin-bottom: 1rem;
        }
        .review-section .row .content .price {
            font-size: 2.5rem;
            color: #fff;
            margin-bottom: 1rem;
        }
        .review-section .row .content form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .review-section .row .content form label {
            font-size: 1.6rem;
            color: #fff;
        }
        .review-section .row .content form input,
        .review-section .row .content form select,
        .review-section .row .content form textarea {
            width: 100%;
            padding: 1rem;
            font-size: 1.6rem;
            border-radius: .5rem;
            background: #fff;
            color: var(--black);
            text-transform: none;
        }
        .review-section .row .content form textarea {
            resize: vertical;
        }
        .review-section .row .content form button {
            margin-top: 1rem;
            padding: .9rem 3rem;
            font-size: 1.7rem;
            color: #fff;
            background: var(--main-color);
            cursor: pointer;
            border-radius: .5rem;
            text-transform: uppercase;
        }
        .review-section .row .content form button:hover {
            letter-spacing: .2rem;
            background: #e68910;
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="../index.php" class="logo">
            <img src="../images/logo.jpg" alt="Logo Trà Sữa TFT">
        </a>
        <nav class="navbar">
            <div class="nav-left">
                <a href="../index.php#home">Trang chủ</a>
                <a href="../index.php#about">Chúng tôi</a>
                <a href="../index.php#menu">Danh sách</a>
                <a href="../index.php#products">Đánh giá</a>
            </div>
            <div class="nav-right">
                <?php
                session_start();
                if (isset($_SESSION['username'])) {
                    echo '<div class="user-dropdown">';
                    echo '<a href="#" class="username"><i class="fas fa-user"></i> ' . htmlspecialchars($_SESSION['username']) . '</a>';
                    echo '<div class="dropdown-content">';
                    echo '<a href="order_status.php">Trạng thái đơn hàng</a>';
                    echo '<a href="đánh giá/favorites.php">Yêu thích</a>';
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

    <section class="review-section">
        <h1 class="heading">Đặt Hàng <span><?php
            $item_name = isset($_GET['item']) ? htmlspecialchars(urldecode($_GET['item'])) : 'Món ăn';
            echo $item_name;
        ?></span></h1>
        <div class="row">
            <div class="image">
                <img src="<?php
                    if (isset($_GET['id'])) {
                        $product_id = htmlspecialchars($_GET['id']);
                        $item_image = '../images/product-' . $product_id . '.png';
                        echo $item_image;
                    } else {
                        if (isset($_GET['image'])) {
                            echo htmlspecialchars(urldecode($_GET['image']));
                        } else {
                            echo 'images/default_item.png';
                        }
                    }
                ?>" alt="<?php echo $item_name; ?>">
            </div>
            <div class="content">
                <h3><?php echo $item_name; ?></h3>
                <div class="price"><?php
                    $item_price = isset($_GET['price']) ? htmlspecialchars($_GET['price']) : '0';
                    echo number_format($item_price, 0, ',', '.'); ?>Đ
                </div>
                <form action="DulieuOrder.php" method="POST">
                    <label for="quantity">Số lượng:</label>
                    <input type="number" id="quantity" name="quantity" min="1" value="1" required>

                    <label for="size">Kích thước:</label>
                    <select id="size" name="size">
                        <option value="Nhỏ">Nhỏ</option>
                        <option value="Vừa" selected>Vừa</option>
                        <option value="Lớn">Lớn</option>
                    </select>

                    <label for="name">Họ và tên:</label>
                    <input type="text" id="name" name="name" placeholder="Nhập họ và tên" required>

                    <label for="phone">Số điện thoại:</label>
                    <input type="tel" id="phone" name="phone" placeholder="Nhập số điện thoại" required>

                    <label for="address">Địa chỉ giao hàng:</label>
                    <textarea id="address" name="address" placeholder="Nhập địa chỉ giao hàng" required></textarea>

                    <label for="notes">Ghi chú thêm:</label>
                    <textarea id="notes" name="notes" placeholder="Ví dụ: Ít đường, ít đá..."></textarea>

                    <input type="hidden" name="item_name" value="<?php echo $item_name; ?>">
                    <input type="hidden" name="item_price" value="<?php echo $item_price; ?>">
                    <?php if (isset($_GET['id'])): ?>
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
                    <?php endif; ?>

                    <button type="submit">Xác nhận đặt hàng</button>
                </form>
            </div>
        </div>
    </section>
</body>
</html>