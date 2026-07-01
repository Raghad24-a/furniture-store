<?php
session_start();
require_once "db.php";

$id = $_GET['id'] ?? '';
if (empty($id)) {
    header("Location: items_list.php");
    exit();
}

$sql = "SELECT * FROM products WHERE id = '$id'";
$result = mysqli_query($connection, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Details - StyleX Furniture</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <span class="logo">🪑 StyleX Furniture</span>
    <ul>
<?php
$user = null;
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) {
        $user = "Admin";
    } else {
        $user = "Customer";
    }
}
if ($user == null) { ?>
        <li><a href="home.php">Home</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
<?php } else {
    if ($user == "Admin") { ?>
        <li><a href="home.php">Home</a></li>
        <li><a href="admin.php">Dashboard</a></li>
        <li><a href="add_item.php">New Item</a></li>
        <li><a href="items_list.php">Items List</a></li>
    <?php } else { ?>
        <li><a href="home.php">Home</a></li>
        <li><a href="checkout.php">Current Order</a></li>
        <li><a href="orders.php">All Orders</a></li>
    <?php } ?>
        <li><a href="logout.php">Logout</a></li>
<?php } ?>
    </ul>
</nav>

<div class="hero-section" style="height:140px;">
    <h1 style="font-size:30px;">Product Details</h1>
</div>

<div style="padding: 40px 20px; max-width:1200px; margin:0 auto;">
    <?php if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) { ?>
    <div class="detail-card">
        <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>"
             onerror="this.src='https://via.placeholder.com/700x300/e8d5b7/5c4033?text=Furniture'">
        <div class="detail-body">
            <span class="category-tag"><?php echo $row['category']; ?></span>
            <h2><?php echo $row['name']; ?></h2>
            <p><?php echo $row['description']; ?></p>
            <div class="detail-price">SAR <?php echo number_format($row['price'], 2); ?></div>

            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) { ?>
                    <form action="remove_item.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn-danger">Remove Item</button>
                    </form>
                <?php } else { ?>
                    <form action="current_order.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn-accent">Add to Cart 🛒</button>
                    </form>
                <?php }
            } else { ?>
                <a href="login.php" class="btn-accent">Login to Order</a>
            <?php } ?>
        </div>
    </div>
    <?php }
    } else { ?>
    <div class="error-page">
        <h3>No items to display, please add some items first.</h3>
    </div>
    <?php } ?>
</div>

<footer>
    <p>📱 Instagram | ✉️ Email: info@stylexfurniture.com | ☎ Phone: (+966) 011-4353-545</p>
    <p>&copy; 2025 StyleX Furniture. All rights reserved.</p>
</footer>

</body>
</html>
