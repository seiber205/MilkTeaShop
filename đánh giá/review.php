<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
    header("Location: user/dangnhap.php");
    exit();
}

// Kết nối cơ sở dữ liệu
$host = 'localhost';
$username = 'root'; // Thay bằng tên người dùng MySQL của bạn
$password = ''; // Thay bằng mật khẩu MySQL của bạn
$dbname = 'milkteashop'; // Thay bằng tên cơ sở dữ liệu của bạn

$conn = new mysqli($host, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy thông tin sản phẩm từ query string
$item = isset($_GET['item']) ? $_GET['item'] : '';
$image = isset($_GET['image']) ? $_GET['image'] : '';

if (empty($item) || empty($image)) {
    die("Sản phẩm không hợp lệ.");
}

// Lấy user_id từ bảng users dựa trên username
$current_user = $_SESSION['username'];
$sql_user = "SELECT id FROM users WHERE username = ?";
$stmt_user = $conn->prepare($sql_user);
if ($stmt_user === false) {
    die("Lỗi prepare (SELECT user_id): " . $conn->error);
}
$stmt_user->bind_param("s", $current_user);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
    $user_id = $user['id'];
} else {
    die("Người dùng không tồn tại.");
}
$stmt_user->close();

// Lấy product_id từ bảng products dựa trên $item
$sql_product = "SELECT id FROM products WHERE name = ?";
$stmt_product = $conn->prepare($sql_product);
if ($stmt_product === false) {
    die("Lỗi prepare (SELECT product_id): " . $conn->error);
}
$stmt_product->bind_param("s", $item);
$stmt_product->execute();
$result_product = $stmt_product->get_result();

if ($result_product->num_rows > 0) {
    $product = $result_product->fetch_assoc();
    $product_id = $product['id'];
} else {
    die("Sản phẩm không tồn tại trong cơ sở dữ liệu.");
}
$stmt_product->close();

// Xử lý gửi bình luận và đánh giá
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

    // Kiểm tra dữ liệu đầu vào
    if ($rating < 1 || $rating > 5 || empty($comment)) {
        $error = "Vui lòng chọn số sao (1-5) và nhập bình luận.";
    } else {
        // Lưu đánh giá vào cơ sở dữ liệu
        $sql = "INSERT INTO reviews (product_id, user_id, item, rating, comment) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Lỗi prepare (INSERT): " . $conn->error);
        }
        $stmt->bind_param("iisis", $product_id, $user_id, $item, $rating, $comment);

        if ($stmt->execute()) {
            $success = "Đánh giá của bạn đã được gửi!";
        } else {
            $error = "Lỗi khi gửi đánh giá: " . $conn->error;
        }
        $stmt->close();
    }
}

// Lấy danh sách bình luận
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Chỉ cho phép các giá trị hợp lệ cho $sort
$allowed_sorts = [
    'newest' => 'created_at DESC',
    'highest' => 'rating DESC',
    'lowest' => 'rating ASC'
];
$order_by = isset($allowed_sorts[$sort]) ? $allowed_sorts[$sort] : $allowed_sorts['newest'];

// Lấy bình luận và join với bảng users để lấy username
$sql = "SELECT reviews.*, users.username 
        FROM reviews 
        LEFT JOIN users ON reviews.user_id = users.id 
        WHERE reviews.product_id = ? 
        ORDER BY $order_by";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Lỗi prepare (SELECT): " . $conn->error);
}

$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

$reviews = [];
while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đánh giá - <?php echo htmlspecialchars($item); ?></title>
    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- custom css file link -->
    <link rel="stylesheet" href="review.css">
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

    <!-- review section starts -->
    <section class="review-section">
        <h1 class="heading">Đánh giá <span><?php echo htmlspecialchars($item); ?></span></h1>
        <div class="review-container">
            <!-- Ảnh sản phẩm -->
            <div class="product-image">
                <img src="../<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($item); ?>">
            </div>

            <!-- Bình luận và đánh giá -->
            <div class="review-content">
                <!-- Form đánh giá -->
                <div class="review-form">
                    <h3>Đánh giá của bạn</h3>
                    <?php if (isset($error)) { ?>
                        <p class="error"><?php echo $error; ?></p>
                    <?php } elseif (isset($success)) { ?>
                        <p class="success"><?php echo $success; ?></p>
                    <?php } ?>
                    <form action="" method="POST">
                        <div class="rating">
                            <label>Chọn số sao:</label>
                            <select name="rating" required>
                                <option value="1">1 sao</option>
                                <option value="2">2 sao</option>
                                <option value="3">3 sao</option>
                                <option value="4">4 sao</option>
                                <option value="5">5 sao</option>
                            </select>
                        </div>
                        <div class="comment">
                            <label>Bình luận:</label>
                            <textarea name="comment" rows="4" required></textarea>
                        </div>
                        <button type="submit" name="submit_review" class="btn">Gửi đánh giá</button>
                    </form>
                </div>

                <!-- Lọc bình luận -->
                <div class="filter">
                    <h3>Lọc bình luận</h3>
                    <a href="review.php?item=<?php echo urlencode($item); ?>&image=<?php echo urlencode($image); ?>&sort=newest" class="btn <?php echo $sort == 'newest' ? 'active' : ''; ?>">Mới nhất</a>
                    <a href="review.php?item=<?php echo urlencode($item); ?>&image=<?php echo urlencode($image); ?>&sort=highest" class="btn <?php echo $sort == 'highest' ? 'active' : ''; ?>">Đánh giá cao nhất</a>
                    <a href="review.php?item=<?php echo urlencode($item); ?>&image=<?php echo urlencode($image); ?>&sort=lowest" class="btn <?php echo $sort == 'lowest' ? 'active' : ''; ?>">Đánh giá thấp nhất</a>
                </div>

                <!-- Danh sách bình luận -->
                <div class="review-list">
                    <h3>Danh sách bình luận</h3>
                    <?php if (count($reviews) > 0) { ?>
                        <?php foreach ($reviews as $review) { ?>
                            <div class="review-item">
                                <div class="review-header">
                                    <span class="username"><?php echo htmlspecialchars($review['username']); ?></span>
                                    <span class="rating">
                                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                                            <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'filled' : ''; ?>"></i>
                                        <?php } ?>
                                    </span>
                                </div>
                                <p class="comment"><?php echo htmlspecialchars($review['comment']); ?></p>
                                <span class="date"><?php echo date('d/m/Y H:i', strtotime($review['created_at'])); ?></span>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="empty">Chưa có bình luận nào cho sản phẩm này.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <!-- review section ends -->

    <!-- custom js file link -->
    <script src=""></script>
</body>
</html>