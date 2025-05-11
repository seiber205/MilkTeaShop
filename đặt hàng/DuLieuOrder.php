<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Hàng Thành Công - Trà Sữa TFT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="order.css">
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
            <a href="../index.php#products">Sản phẩm</a>
            </div>
            <div class="nav-right">
            <?php
            session_start();
            if (isset($_SESSION['username'])) {
                echo '<a href="#">' . htmlspecialchars($_SESSION['username']) . '</a>';
                echo '<a href="../user/dangxuat.php">Đăng xuất</a>';
            } else {
                echo '<a href="../user/dangnhap.php">Đăng nhập</a>';
                echo '<a href="../user/dangky.php">Đăng ký</a>';
            }
            ?>
            </div>
        </nav>
      ]
    </header>

    <section class="success-container">
        <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $servername = "localhost";
        $username = "root12";
        $password = "";
        $dbname = "milkteashop";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("<h2 class='error'>Lỗi kết nối CSDL!</h2><p>" . $conn->connect_error . "</p>");
        }

        // Kiểm tra và lấy dữ liệu từ form
        $item_name = isset($_POST['item_name']) ? $_POST['item_name'] : '';
        $item_price = isset($_POST['item_price']) ? floatval($_POST['item_price']) : 0;
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        $size = isset($_POST['size']) ? $_POST['size'] : '';
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
        $address = isset($_POST['address']) ? $_POST['address'] : '';
        $notes = isset($_POST['notes']) ? $_POST['notes'] : '';

        // Debug dữ liệu nhận được
        

        $sql = "INSERT INTO orders (item_name, item_price, quantity, size, customer_name, phone, address, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdssssss", $item_name, $item_price, $quantity, $size, $name, $phone, $address, $notes);

        if ($stmt->execute()) {
            echo "<h2>Đặt hàng thành công!</h2>";
            echo "<p>Cảm ơn bạn đã đặt hàng tại Trà Sữa TFT. Chúng tôi sẽ liên hệ sớm!</p>";
            echo "<a href='../index.php'>Quay lại trang chủ</a>";
        } else {
            echo "<h2 class='error'>Lỗi khi đặt hàng!</h2>";
            echo "<p>Lỗi: " . $stmt->error . "</p>";
            echo "<p>Vui lòng thử lại sau.</p>";
            echo "<a href='order.php?item=" . urlencode($item_name) . "&price=" . $item_price . "'>Thử lại</a>";
        }

        $stmt->close();
        $conn->close();
        ?>
    </section>
</body>
</html>