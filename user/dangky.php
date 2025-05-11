<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký - Trà Sữa TFT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="dangnhapvaky.css">
</head>
<body>
<header class="header">
    <a href="../index.php" class="logo">
        <img src="../images/logo.jpg" alt="Logo Trà Sữa TFT">
    </a>
    <nav class="navbar">
        <div class="nav-left">
            <a href="#home">Trang chủ</a>
            <a href="#about">Chúng tôi</a>
            <a href="#menu">Danh sách</a>
            <a href="#products">Sản phẩm</a>
        </div>
        <div class="nav-right">
            <?php
            session_start();
            if (isset($_SESSION['username'])) {
                echo '<a href="#" class="username"><i class="fas fa-user"></i> ' . htmlspecialchars($_SESSION['username']) . '</a>';
                echo '<a href="dangxuat.php" class="logout">Đăng xuất</a>';
            } else {
                echo '<a href="dangnhap.php">Đăng nhập</a>';
                echo '<a href="dangky.php">Đăng ký</a>';
            }
            ?>
        </div>
    </nav>
 
</header>

    <section class="register-container">
        <h2>Đăng Ký</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $servername = "localhost";
            $username = "root12";
            $password = "";
            $dbname = "milkteashop";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                echo "<p class='error'>Lỗi kết nối CSDL: " . $conn->connect_error . "</p>";
            } else {
                $username = trim($_POST['username']);
                $password = trim($_POST['password']);

                if (empty($username) || empty($password)) {
                    echo "<p class='error'>Vui lòng điền đầy đủ thông tin!</p>";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $username, $hashed_password);

                    if ($stmt->execute()) {
                        echo "<p>Đăng ký thành công! Vui lòng <a href='login.php'>đăng nhập</a>.</p>";
                    } else {
                        echo "<p class='error'>Tên đăng nhập đã tồn tại!</p>";
                    }

                    $stmt->close();
                }
                $conn->close();
            }
        }
        ?>
        <form action="dangky.php" method="POST">
            <label for="username">Tên đăng nhập:</label>
            <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập" required>
            
            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
            
            <button type="submit">Đăng ký</button>
        </form>
    </section>
</body>
</html>