<?php
// Kiểm tra trạng thái đăng nhập bằng cookies
if (!isset($_COOKIE['username']) || $_COOKIE['role'] !== 'admin') {
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

// Xử lý xác nhận hoặc hủy đơn hàng
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == 'confirm') {
        $sql_update = "UPDATE orders SET status = 'completed' WHERE id = ?";
    } elseif ($action == 'cancel') {
        $sql_update = "UPDATE orders SET status = 'canceled' WHERE id = ?";
    }

    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("i", $id);
    $stmt_update->execute();
    $stmt_update->close();

    header("Location: admin_dashboard.php");
    exit();
}

// Lấy danh sách người dùng
$sql_users = "SELECT id, username, role FROM users";
$result_users = $conn->query($sql_users);

// Lấy danh sách đơn hàng
$sql_orders = "SELECT * FROM orders";
$result_orders = $conn->query($sql_orders);

// Lấy danh sách đánh giá, liên kết với bảng products để lấy tên sản phẩm
$sql_reviews = "SELECT r.id, r.product_id, p.name AS product_name, r.username, r.rating, r.comment, r.created_at 
                FROM reviews r 
                LEFT JOIN products p ON r.product_id = p.id";
$result_reviews = $conn->query($sql_reviews);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Quản Lý - Trà Sữa TFT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="admin.css">
    <style>
        /* Ghi đè màu tím cho liên kết đã truy cập */
        .header .navbar a:visited {
            color: #fff;
        }
        .header .navbar a:visited:hover {
            color: #f39c12; /* Màu cam, khớp với --main-color */
        }
        /* Định dạng trạng thái đơn hàng */
        .status-pending {
            color: #ffcc00; /* Vàng */
        }
        .status-completed {
            color: #55ff55; /* Xanh lá */
        }
        .status-canceled {
            color: #ff5555; /* Đỏ */
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="admin_dashboard.php" class="logo">
            <img src="../images/logo.jpg" alt="Logo Trà Sữa TFT">
        </a>
        <nav class="navbar">
            <div class="nav-left">
                <a href="admin_dashboard.php">Quản lý</a>
            </div>
            <div class="nav-right">
                <a href="#" class="username"><i class="fas fa-user"></i> <?php echo htmlspecialchars($_COOKIE['username']); ?></a>
                <a href="../user/dangxuat.php" class="logout">Đăng xuất</a>
            </div>
        </nav>
    </header>

    <section class="admin-container">
        <h2>Bảng Quản Lý</h2>
        
        <!-- Quản lý người dùng -->
        <div class="manage-section">
            <h3>Quản lý người dùng</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên đăng nhập</th>
                        <th>Vai trò</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_users->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo $row['role']; ?></td>
                            <td>
                                <a href="delete_user.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Xóa người dùng này?');">Xóa</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Quản lý đơn hàng -->
        <div class="manage-section">
            <h3>Quản lý đơn hàng</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Món</th>
                        <th>Giá (1 ly)</th>
                        <th>Số lượng</th>
                        <th>Tổng giá</th>
                        <th>Khách hàng</th>
                        <th>Địa chỉ</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_orders->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                            <td><?php echo number_format($row['item_price'], 0, ',', '.'); ?>Đ</td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo number_format($row['total_price'], 0, ',', '.'); ?>Đ</td>
                            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td class="status-<?php echo $row['status']; ?>">
                                <?php
                                if ($row['status'] == 'pending') {
                                    echo 'Đang chờ xử lý';
                                } elseif ($row['status'] == 'completed') {
                                    echo 'Thành công';
                                } elseif ($row['status'] == 'canceled') {
                                    echo 'Bị hủy';
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'pending'): ?>
                                    <a href="admin_dashboard.php?action=confirm&id=<?php echo $row['id']; ?>" onclick="return confirm('Xác nhận hoàn thành đơn hàng này?');">Xác nhận</a> |
                                    <a href="admin_dashboard.php?action=cancel&id=<?php echo $row['id']; ?>" onclick="return confirm('Hủy đơn hàng này?');">Hủy</a>
                                <?php else: ?>
                                    <a href="delete_order.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Xóa đơn hàng này?');">Xóa</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Quản lý đánh giá -->
        <div class="manage-section">
            <h3>Quản lý đánh giá</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sản phẩm</th>
                        <th>Người dùng</th>
                        <th>Điểm</th>
                        <th>Bình luận</th>
                        <th>Ngày đăng</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_reviews->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['product_name'] ?? 'Sản phẩm không tồn tại'); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo $row['rating']; ?></td>
                            <td><?php echo htmlspecialchars($row['comment']); ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td>
                                <a href="delete_review.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Xóa đánh giá này?');">Xóa</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>