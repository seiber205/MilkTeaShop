<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Bắt đầu session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Trà Sữa TFT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="dangnhapvaky.css">
    <style>
        .cookie-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .cookie-modal-content {
            background: #fff;
            padding: 2rem;
            border-radius: 0.5rem;
            text-align: center;
            max-width: 400px;
            width: 90%;
        }
        .cookie-modal-content p {
            font-size: 1.6rem;
            margin-bottom: 2rem;
        }
        .cookie-modal-content button {
            padding: 1rem 2rem;
            margin: 0 1rem;
            border: none;
            border-radius: 0.3rem;
            cursor: pointer;
            font-size: 1.4rem;
        }
        .cookie-modal-content .accept-btn {
            background: #4CAF50;
            color: #fff;
        }
        .cookie-modal-content .decline-btn {
            background: #f44336;
            color: #fff;
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
                <a href="../index.php#products">Sản phẩm</a>
            </div>
            <div class="nav-right">
                <?php
                if (isset($_SESSION['username']) || isset($_COOKIE['username'])) {
                    $username = isset($_SESSION['username']) ? $_SESSION['username'] : $_COOKIE['username'];
                    echo '<div class="user-dropdown">';
                    echo '<a href="#" class="username"><i class="fas fa-user"></i> ' . htmlspecialchars($username) . '</a>';
                    echo '<div class="dropdown-content">';
                    echo '<a href="favorites.php">Yêu thích</a>';
                    echo '<a href="dangxuat.php">Đăng xuất</a>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo '<a href="dangnhap.php">Đăng nhập</a>';
                    echo '<a href="dangky.php">Đăng ký</a>';
                }
                ?>
            </div>
        </nav>
    </header>

    <section class="login-container">
        <h2>Đăng Nhập</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['cookie_response'])) {
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

                $sql = "SELECT password, role FROM users WHERE username = ?";
                $stmt = $conn->prepare($sql);
                if ($stmt === false) {
                    echo "<p class='error'>Lỗi chuẩn bị truy vấn: " . $conn->error . "</p>";
                } else {
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        if (password_verify($password, $row['password'])) {
                            // Lưu thông tin vào session trước
                            $_SESSION['username'] = $username;
                            $_SESSION['role'] = $row['role'];
                            $_SESSION['pending_cookie'] = true; // Đánh dấu để hỏi về cookie

                            // Hiển thị modal hỏi về cookie
                            echo '<div id="cookieModal" class="cookie-modal" style="display: flex;">
                                    <div class="cookie-modal-content">
                                        <p>Bạn có muốn sử dụng cookies để giữ trạng thái đăng nhập không?</p>
                                        <form method="POST" action="dangnhap.php">
                                            <input type="hidden" name="cookie_response" value="accept">
                                            <button type="submit" class="accept-btn">Có</button>
                                            <input type="hidden" name="username" value="' . htmlspecialchars($username) . '">
                                            <input type="hidden" name="role" value="' . htmlspecialchars($row['role']) . '">
                                        </form>
                                        <form method="POST" action="dangnhap.php">
                                            <input type="hidden" name="cookie_response" value="decline">
                                            <button type="submit" class="decline-btn">Không</button>
                                            <input type="hidden" name="username" value="' . htmlspecialchars($username) . '">
                                            <input type="hidden" name="role" value="' . htmlspecialchars($row['role']) . '">
                                        </form>
                                    </div>
                                </div>';
                        } else {
                            echo "<p class='error'>Sai mật khẩu!</p>";
                        }
                    } else {
                        echo "<p class='error'>Tên đăng nhập không tồn tại!</p>";
                    }

                    $stmt->close();
                }
                $conn->close();
            }
        } elseif (isset($_POST['cookie_response'])) {
            // Xử lý sau khi người dùng chọn có/không dùng cookies
            $username = $_POST['username'];
            $role = $_POST['role'];

            // Lưu lại vào session
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            if ($_POST['cookie_response'] == 'accept') {
                // Lưu thông tin vào cookies (hiệu lực 30 ngày)
                setcookie('username', $username, time() + (30 * 24 * 60 * 60), "/"); // 30 ngày
                setcookie('role', $role, time() + (30 * 24 * 60 * 60), "/");
            }

            // Chuyển hướng sau khi xử lý
            if ($role == 'admin') {
                setcookie('admin_username', $username, time() + (30 * 24 * 60 * 60), "/"); // Lưu cookie admin_username
                header("Location: ../quanly/admin_dashboard.php"); // Sửa đường dẫn
                exit();
            } else {
                header("Location: ../index.php");
                exit();
            }
        }
        ?>
        <form action="dangnhap.php" method="POST">
            <label for="username">Tên đăng nhập:</label>
            <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập" required>
            
            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
            
            <button type="submit">Đăng nhập</button>
        </form>
    </section>
</body>
</html>