<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];
$sql = "SELECT o.order_id, o.quantity, o.total_price, o.order_date, p.name, p.price, p.image, p.category
        FROM orders o
        JOIN products p ON o.product_id = p.id
        WHERE o.user_id = '$user_id'
        ORDER BY o.order_date DESC";
$result = mysqli_query($connection, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders - StyleX Furniture</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <span class="logo">🪑 StyleX Furniture</span>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="checkout.php">Current Order</a></li>
        <li><a href="orders.php" class="active">All Orders</a></li>
        <li><a href="logout.php">Log out</a></li>
    </ul>
</nav>

<div class="hero-section" style="height:160px;">
    <h1 style="font-size:36px;">📦 My Orders</h1>
    <p>Welcome, <?php echo $_SESSION['name']; ?></p>
</div>

<div class="items-container">
    <h2>Order History</h2>

    <?php if (mysqli_num_rows($result) > 0) { ?>
    <table class="orders-table">
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>Category</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
        <?php $i = 1; while ($row = mysqli_fetch_array($result)) { ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['category']; ?></td>
            <td><?php echo number_format($row['price'], 2); ?> SAR</td>
            <td><?php echo $row['quantity']; ?></td>
            <td><?php echo number_format($row['total_price'], 2); ?> SAR</td>
            <td><?php echo date('Y-m-d', strtotime($row['order_date'])); ?></td>
            <td><span class="status-badge">Processing</span></td>
        </tr>
        <?php } ?>
    </table>
    <?php } else { ?>
    <div class="error-page">
        <h3>No orders found.</h3>
        <p style="margin-top:10px;"><a href="home.php" class="btn-primary">Start Shopping</a></p>
    </div>
    <?php } ?>
</div>

<footer>
    <p>📱 Instagram | ✉️ Email: info@stylexfurniture.com | ☎ Phone: (+966) 011-4353-545</p>
    <p>&copy; 2025 StyleX Furniture. All rights reserved.</p>
</footer>

</body>
</html>
