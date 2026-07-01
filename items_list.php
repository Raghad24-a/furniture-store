<?php
session_start();
require_once "db.php";

// Check if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) {
        // Admin can access
    } else {
        die("You can't access this page. Admin Area.");
        header("Location: home.php");
        exit();
    }
}

$sql = "SELECT * FROM products";
$result = mysqli_query($connection, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items List - StyleX Furniture</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <span class="logo">🪑 StyleX Furniture</span>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="admin.php">Dashboard</a></li>
        <li><a href="add_item.php">New Item</a></li>
        <li><a href="items_list.php" class="active">Items List</a></li>
        <li><a href="logout.php">Log out</a></li>
    </ul>
</nav>

<div class="hero-section" style="height:160px;">
    <h1 style="font-size:36px;">📋 Items List</h1>
    <p>Manage your furniture inventory</p>
</div>

<div class="items-container">
    <h2>Admin Dashboard</h2>
    <h3 style="margin-bottom:20px; color:var(--text-light); font-family:'Jost',sans-serif; font-size:16px;">Manage Products</h3>

    <?php if (mysqli_num_rows($result) > 0) { ?>
    <div class="products-grid">
        <?php while ($row = mysqli_fetch_array($result)) { ?>
        <div class="item-card">
            <a href="item_details.php?id=<?php echo $row['id']; ?>">
                <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" class="product-img"
                     onerror="this.style.display='none'">
                <div class="item-desc">
                    <h3><?php echo $row['name']; ?></h3>
                    <p class="dear"><?php echo $row['description']; ?></p>
                </div>
            </a>
            <div class="item-footer">
                <span class="price">SAR <?php echo number_format($row['price'], 2); ?></span>
                <?php if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) { ?>
                <form action="remove_item.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="btn-danger">Remove</button>
                </form>
                <?php } else { ?>
                <form action="current_order.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="btn-accent">Add to Cart</button>
                </form>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php } else { ?>
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
