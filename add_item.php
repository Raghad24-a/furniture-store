<?php
session_start();
require_once "db.php";

// Check if logged in as admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
} else {
    if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 1) {
        die("Access Denied! Admin Area only.");
    }
}

$nameError = $priceError = $imgError = $descriptionError = $categoryError = "";
$name = $price = $img_url = $description = $category = "";
$validated = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty($_POST['name'])) {
        $nameError = "Item name is required";
        $validated = false;
    } else {
        $name = $_POST['name'];
    }
    // Validate price
    if (empty($_POST['price'])) {
        $priceError = "Item Price is required";
        $validated = false;
    } else {
        if (!is_numeric($_POST['price'])) {
            $priceError = "Item Price should be a number";
            $validated = false;
        } else {
            $price = $_POST['price'];
        }
    }
    // Validate image url
    if (empty($_POST['img_url'])) {
        $imgError = "Image URL is required";
        $validated = false;
    } else {
        $img_url = $_POST['img_url'];
    }
    // Validate description
    if (empty($_POST['description'])) {
        $descriptionError = "Item Description is required";
        $validated = false;
    } else {
        $description = $_POST['description'];
    }
    // Category
    $category = $_POST['category'] ?? 'Other';

    if ($validated) {
        $sql = "INSERT INTO products (name, price, image, description, category) VALUES ('$name', '$price', '$img_url', '$description', '$category')";
        if (mysqli_query($connection, $sql) == true) {
            // New item inserted successfully. Redirect to the items list page
            header("Location: items_list.php");
            exit();
        } else {
            die("Something went wrong. Please try again later.");
        }
    }
    // Close connection
    mysqli_close($connection);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item - StyleX Furniture</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <span class="logo">🪑 StyleX Furniture</span>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="admin.php">Dashboard</a></li>
        <li><a href="add_item.php" class="active">New Item</a></li>
        <li><a href="items_list.php">Items List</a></li>
        <li><a href="logout.php">Log out</a></li>
    </ul>
</nav>

<div class="hero-section" style="height:160px;">
    <h1 style="font-size:36px;">➕ Add New Item</h1>
</div>

<div class="form-container" style="max-width:560px;">
    <h2>Add Furniture</h2>

    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="name">Item Name</label>
        <input type="text" name="name" id="name" placeholder="Nike Shoes" value="<?php echo $name; ?>">
        <p class="error-msg"><?php echo $nameError; ?></p>

        <label for="price">Item Price (SAR)</label>
        <input type="text" name="price" id="price" placeholder="149.99" value="<?php echo $price; ?>">
        <p class="error-msg"><?php echo $priceError; ?></p>

        <label for="img_url">Item Image URL</label>
        <input type="text" name="img_url" id="img_url" placeholder="https://example.com/img.jpg" value="<?php echo $img_url; ?>">
        <p class="error-msg"><?php echo $imgError; ?></p>

        <label for="category">Category</label>
        <select name="category" id="category">
            <option value="Living Room" <?php echo ($category=='Living Room') ? 'selected' : ''; ?>>Living Room</option>
            <option value="Dining Room" <?php echo ($category=='Dining Room') ? 'selected' : ''; ?>>Dining Room</option>
            <option value="Office" <?php echo ($category=='Office') ? 'selected' : ''; ?>>Office</option>
            <option value="Bedroom" <?php echo ($category=='Bedroom') ? 'selected' : ''; ?>>Bedroom</option>
            <option value="Other" <?php echo ($category=='Other') ? 'selected' : ''; ?>>Other</option>
        </select>

        <label for="description">Description</label>
        <textarea name="description" id="description" style="width:100%; height:80px; padding:11px 14px; border:1px solid #ddd; border-radius:5px; font-family:'Jost',sans-serif; font-size:14px; background:var(--bg);" placeholder="Describe the furniture item..."><?php echo $description; ?></textarea>
        <p class="error-msg"><?php echo $descriptionError; ?></p>

        <button type="submit">Save Item</button>
    </form>
</div>

<footer>
    <p>📱 Instagram | ✉️ Email: info@stylexfurniture.com | ☎ Phone: (+966) 011-4353-545</p>
    <p>&copy; 2025 StyleX Furniture. All rights reserved.</p>
</footer>

</body>
</html>
