<?php
session_start();
// include the DB connection file
require_once "db.php";

$img = "";
$sql = "SELECT * FROM products";
$result = mysqli_query($connection, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>StyleX Furniture - Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
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
        <li><a href="home.php" class="active">Home</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
<?php } else {
    if ($user == "Admin") { ?>
        <li><a href="home.php">Home</a></li>
        <li><a href="admin.php">Dashboard</a></li>
        <li><a href="add_item.php">New Item</a></li>
        <li><a href="items_list.php">Items List</a></li>
<?php } else { ?>
        <li><a href="home.php" class="active">Home</a></li>
        <li><a href="checkout.php">Current Order</a></li>
        <li><a href="orders.php">All Orders</a></li>
<?php } ?>
        <li><a href="logout.php">Logout</a></li>
<?php } ?>
    </ul>
</nav>

<!-- HERO SECTION -->
<div class="hero-section">
    <h1>🛋️ StyleX Furniture</h1>
    <p>Elegance for Every Space</p>
</div>

<!-- PRODUCTS SECTION -->
<div class="items-container">
    <h2>Our Furniture Collection</h2>

    <!-- Category Filter -->
    <div class="category-filter">
        <button class="category-btn active" onclick="filterCategory('all')">All</button>
        <button class="category-btn" onclick="filterCategory('Living Room')">Living Room</button>
        <button class="category-btn" onclick="filterCategory('Dining Room')">Dining Room</button>
        <button class="category-btn" onclick="filterCategory('Office')">Office</button>
        <button class="category-btn" onclick="filterCategory('Bedroom')">Bedroom</button>
    </div>

    <?php if (mysqli_num_rows($result) > 0) { ?>
    <div class="products-grid" id="productsGrid">
        <?php while ($row = mysqli_fetch_array($result)) { ?>
        <div class="item-card" data-category="<?php echo $row['category']; ?>">
            <a href="item_details.php?id=<?php echo $row['id']; ?>">
                <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" class="product-img"
                     onerror="this.style.display='none';this.nextSibling.style.display='flex'">
                <div class="product-img" style="display:none;">🛋️</div>
                <div class="item-desc">
                    <h3><?php echo $row['name']; ?></h3>
                    <p class="dear"><?php echo $row['description']; ?></p>
                </div>
            </a>
            <div class="item-footer">
                <span class="price">SAR <?php echo number_format($row['price'], 2); ?></span>
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                    if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) { ?>
                        <form action="remove_item.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn-danger">Remove</button>
                        </form>
                    <?php } else { ?>
                        <form action="current_order.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn-accent">Add to Cart</button>
                        </form>
                    <?php }
                } else { ?>
                    <a href="login.php" class="btn-accent">Add to Cart</a>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php } else { ?>
    <div class="error-page">
        <h3>No items to display. Please add some items first.</h3>
    </div>
    <?php } ?>
</div>

<!-- WHY US SECTION -->
<div class="why-section">
    <h2>Why Choose StyleX?</h2>
    <div class="why-grid">
        <div class="why-card">
            <div class="icon">🚚</div>
            <h3>Fast Delivery</h3>
            <p>We deliver your furniture safely and on time, right to your door.</p>
        </div>
        <div class="why-card">
            <div class="icon">🏆</div>
            <h3>Premium Quality</h3>
            <p>All our pieces are crafted with the finest materials for lasting durability.</p>
        </div>
        <div class="why-card">
            <div class="icon">💳</div>
            <h3>Easy Payment</h3>
            <p>Multiple payment options including credit cards and cash on delivery.</p>
        </div>
    </div>
</div>

<footer>
    <p>📱 Instagram | ✉️ Email: info@stylexfurniture.com | ☎ Phone: (+966) 011-4353-545</p>
    <p>&copy; 2025 StyleX Furniture. All rights reserved.</p>
</footer>

<script>
function filterCategory(cat) {
    const cards = document.querySelectorAll('.item-card');
    document.querySelectorAll('.category-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    cards.forEach(card => {
        if (cat === 'all' || card.dataset.category === cat) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>

</body>
</html>
