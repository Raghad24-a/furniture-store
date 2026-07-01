<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id  = $_SESSION['id'];
    $fullName = $_POST['fullName'] ?? '';
    $address  = $_POST['address'] ?? '';
    $payment  = $_POST['payment'] ?? '';

    if (empty($fullName) || empty($address) || empty($payment)) {
        header("Location: checkout.php");
        exit();
    }

    // Order is confirmed — in a real app you'd update status in DB
    // For now, just show success message
    $success = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmed - StyleX Furniture</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <span class="logo">🪑 StyleX Furniture</span>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="orders.php">My Orders</a></li>
        <li><a href="logout.php">Log out</a></li>
    </ul>
</nav>

<div class="logout-message" style="margin-top:60px;">
    <h2 style="font-size:40px;">🎉</h2>
    <h2>Order Placed Successfully!</h2>
    <p>Thank you, <?php echo htmlspecialchars($_POST['fullName'] ?? $_SESSION['name']); ?>! Your furniture is on its way.</p>
    <p style="font-size:13px; margin-top:5px;">Delivery to: <?php echo htmlspecialchars($_POST['address'] ?? ''); ?></p>
    <br>
    <a href="home.php" class="btn-primary">Continue Shopping</a>
    &nbsp;&nbsp;
    <a href="orders.php" class="btn-accent">View My Orders</a>
</div>

<footer>
    <p>📱 Instagram | ✉️ Email: info@stylexfurniture.com</p>
    <p>&copy; 2025 StyleX Furniture. All rights reserved.</p>
</footer>

</body>
</html>
