<?php
session_start();
require_once "db.php";

// Check if logged in as admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: admin_login.php");
    exit();
}
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
    die("Access Denied! Admin Area only.");
}

$sql = "SELECT * FROM products";
$result = mysqli_query($connection, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - StyleX Furniture</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <span class="logo">🪑 StyleX Furniture</span>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="admin.php" class="active">Admin Panel</a></li>
        <li><a href="add_item.php">New Item</a></li>
        <li><a href="items_list.php">Items List</a></li>
        <li><a href="logout.php">Log out</a></li>
    </ul>
</nav>

<div class="hero-section" style="height:160px;">
    <h1 style="font-size:36px;">⚙️ Admin Dashboard</h1>
    <p>Welcome, <?php echo $_SESSION['name']; ?></p>
</div>

<div class="admin-dashboard">
    <h2>Manage Furniture Products</h2>

    <a href="add_item.php" class="add-product-btn">+ Add New Product</a>

    <br><br>

    <table class="admin-table" id="admin-product-table">
        <tr>
            <th>Product Name</th>
            <th>Category</th>
            <th>Price (SAR)</th>
            <th>Actions</th>
        </tr>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) { ?>
        <tr>
            <td>
                <input type="text" value="<?php echo $row['name']; ?>"
                       data-name="<?php echo $row['id']; ?>" class="edit-name">
            </td>
            <td><?php echo $row['category']; ?></td>
            <td>
                <input type="number" value="<?php echo $row['price']; ?>"
                       data-price="<?php echo $row['id']; ?>" class="edit-price" step="0.01">
            </td>
            <td>
                <form action="remove_item.php" method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="btn-danger" data-del="<?php echo $row['id']; ?>">Delete</button>
                </form>
            </td>
        </tr>
            <?php }
        } else { ?>
        <tr>
            <td colspan="4" style="text-align:center; padding:30px; color:#999;">
                No products found. <a href="add_item.php">Add your first product</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

<footer>
    <p>📱 Instagram | ✉️ Email: info@stylexfurniture.com | ☎ Phone: (+966) 011-4353-545</p>
    <p>&copy; 2025 StyleX Furniture. All rights reserved.</p>
</footer>

</body>
</html>
