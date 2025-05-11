<?php
// Kiểm tra trạng thái đăng nhập bằng cookies
if (!isset($_COOKIE['username'])) {
    header("Location: ../user/dangnhap.php");
    exit();
}

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root12";
$password = "";
$dbname = "milkteashop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Lỗi kết nối CSDL: " . $conn->connect_error);
}

// Lấy giá trị customer_name từ cookie và debug
$customer_name = $_COOKIE['username'];
echo "<!-- Debug: customer_name từ cookie = " . htmlspecialchars($customer_name) . " -->";

// Lấy danh sách đơn hàng của khách hàng
$sql_orders = "SELECT * FROM orders WHERE customer_name = ? ORDER BY order_date DESC";
$stmt_orders = $conn->prepare($sql_orders);
if ($stmt_orders === false) {
    die("Lỗi prepare (SELECT orders): " . $conn->error);
}
$stmt_orders->bind_param("s", $customer_name);
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();
$orders = [];
while ($row = $result_orders->fetch_assoc()) {
    $orders[] = $row;
}
$stmt_orders->close();

// Debug: Kiểm tra số lượng đơn hàng tìm thấy
echo "<!-- Debug: Số đơn hàng tìm thấy = " . count($orders) . " -->";

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trạng Thái Đơn Hàng - Trà Sữa TFT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../user/dangnhapvaky.css">
    <style>
        .order-section {
            padding: 9rem 7%;
            background: var(--bg);
            min-height: 100vh;
        }
        .order-section .heading {
            text-align: center;
            padding-bottom: 3.5rem;
            color: #fff;
            text-transform: uppercase;
            font-size: 4rem;
        }
        .order-section .heading span {
            color: var(--main-color);
            text-transform: uppercase;
        }
        .order-section table {
            width: 100%;
            border-collapse: collapse;
            background: var(--black);
            border-radius: 1rem;
            overflow: hidden;
        }
        .order-section th, .order-section td {
            padding: 1.5rem;
            text-align: left;
            font-size: 1.6rem;
            color: #fff;
            border-bottom: 1px solid var(--border);
        }
        .order-section th {
            background: #1a1a1a;
            font-weight: bold;
        }
        .order-section .status-pending {
            color: #ffcc00;
        }
        .order-section .status-completed {
            color: #55ff55;
        }
        .order-section .status-canceled {
            color: #ff5555;
        }
        .order-section .empty {
            color: #fff;
            font-size: 1.6rem;
            text-align: center;
            padding: 2rem;
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
                if (isset($_COOKIE['username'])) {
                    echo '<div class="user-dropdown">';
                    echo '<a href="#" class="username"><i class="fas fa-user"></i> ' . htmlspecialchars($_COOKIE['username']) . '</a>';
                    echo '<div class="dropdown-content">';
                    echo '<a href="order_status.php">Trạng thái đơn hàng</a>';
                    echo '<a href="../đánh giá/favorites.php">Yêu thích</a>';
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

    <section class="order-section">
        <h1 class="heading">Trạng Thái <span>Đơn Hàng</span></h1>
        <?php if (empty($orders)): ?>
            <p class="empty">Bạn chưa có đơn hàng nào.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Món</th>
                        <th>Kích thước</th>
                        <th>Số lượng</th>
                        <th>Tổng giá</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th>Ghi chú</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['item_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['size']); ?></td>
                            <td><?php echo $order['quantity']; ?></td>
                            <td><?php echo number_format($order['total_price'], 0, ',', '.'); ?>Đ</td>
                            <td><?php echo htmlspecialchars($order['phone']); ?></td>
                            <td><?php echo htmlspecialchars($order['address']); ?></td>
                            <td><?php echo htmlspecialchars($order['notes'] ?: 'Không có'); ?></td>
                            <td><?php echo $order['order_date']; ?></td>
                            <td class="status-<?php echo $order['status']; ?>">
                                <?php
                                if ($order['status'] == 'pending') {
                                    echo 'Đang chờ xử lý';
                                } elseif ($order['status'] == 'completed') {
                                    echo 'Thành công';
                                } elseif ($order['status'] == 'canceled') {
                                    echo 'Bị hủy';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</body>
</html>