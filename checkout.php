<?php
session_start();
require_once "db.php";

// Redirect if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

// Handle Add to Cart
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $product_id = $_POST['id'];
    // Get product price
    $prod_sql = "SELECT * FROM products WHERE id = '$product_id'";
    $prod_result = mysqli_query($connection, $prod_sql);
    if (mysqli_num_rows($prod_result) > 0) {
        $product = mysqli_fetch_array($prod_result);
        $total = $product['price'] * 1;
        // Insert into orders with quantity 1 (or update if exists)
        $check = "SELECT * FROM orders WHERE user_id='$user_id' AND product_id='$product_id'";
        $checkRes = mysqli_query($connection, $check);
        if (mysqli_num_rows($checkRes) > 0) {
            // Update quantity
            $existingRow = mysqli_fetch_array($checkRes);
            $newQty = $existingRow['quantity'] + 1;
            $newTotal = $product['price'] * $newQty;
            $upd = "UPDATE orders SET quantity='$newQty', total_price='$newTotal' WHERE user_id='$user_id' AND product_id='$product_id'";
            mysqli_query($connection, $upd);
        } else {
            $ins = "INSERT INTO orders (user_id, product_id, quantity, total_price) VALUES ('$user_id', '$product_id', 1, '$total')";
            mysqli_query($connection, $ins);
        }
    }
}

// Fetch current user orders
$sql = "SELECT o.order_id, o.quantity, o.total_price, o.order_date, p.name, p.price, p.image
        FROM orders o
        JOIN products p ON o.product_id = p.id
        WHERE o.user_id = '$user_id'
        ORDER BY o.order_date DESC";
$result = mysqli_query($connection, $sql);

$subtotal = 0;
$orders_arr = [];
while ($row = mysqli_fetch_array($result)) {
    $subtotal += $row['total_price'];
    $orders_arr[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - StyleX Furniture</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <span class="logo">🪑 StyleX Furniture</span>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="checkout.php" class="active">Current Order</a></li>
        <li><a href="orders.php">All Orders</a></li>
        <li><a href="logout.php">Log out</a></li>
    </ul>
</nav>

<div class="hero-section" style="height:160px;">
    <h1 style="font-size:36px;">🛒 Shopping Cart</h1>
</div>

<div class="items-container">
    <h2>Your Items</h2>

    <?php if (count($orders_arr) > 0) { ?>
    <table class="cart-table">
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Remove</th>
        </tr>
        <?php foreach ($orders_arr as $row) { ?>
        <tr>
            <td>
                <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>"
                     style="width:70px; height:60px; object-fit:cover; border-radius:6px;"
                     onerror="this.src='https://via.placeholder.com/70x60/e8d5b7/5c4033?text=🛋️'">
            </td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo number_format($row['price'], 2); ?> SAR</td>
            <td>
                <input type="number" class="qty-input" value="<?php echo $row['quantity']; ?>"
                       min="1" data-order="<?php echo $row['order_id']; ?>"
                       onchange="updateQty(this, <?php echo $row['price']; ?>)">
            </td>
            <td id="total-<?php echo $row['order_id']; ?>"><?php echo number_format($row['total_price'], 2); ?> SAR</td>
            <td>
                <form action="remove_order.php" method="POST">
                    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                    <button type="submit" class="remove-btn">✕</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>

    <div class="checkout-section" style="margin-top:30px;">
        <p class="subtotal-text">Subtotal: <strong>SAR <?php echo number_format($subtotal, 2); ?></strong></p>
        <h2>Checkout & Payment</h2>
        <form action="place_order.php" method="POST">
            <label for="fullName">Full Name</label>
            <input type="text" id="fullName" name="fullName" value="<?php echo $_SESSION['name']; ?>" required>

            <label for="address">Address</label>
            <input type="text" id="address" name="address" placeholder="Your delivery address" required>

            <label for="payment">Payment Method</label>
            <select id="payment" name="payment" required>
                <option value="">-- Select Method --</option>
                <option value="card">Credit / Debit Card</option>
                <option value="cod">Cash on Delivery</option>
            </select>

            <br><br>
            <button type="submit" class="checkout-btn">Confirm Order ✓</button>
        </form>
    </div>

    <?php } else { ?>
    <div class="error-page">
        <h3>Your cart is empty.</h3>
        <p style="margin-top:10px;"><a href="home.php" class="btn-primary">Shop Now</a></p>
    </div>
    <?php } ?>
</div>

<footer>
    <p>📱 Instagram | ✉️ Email: info@stylexfurniture.com | ☎ Phone: (+966) 011-4353-545</p>
    <p>&copy; 2025 StyleX Furniture. All rights reserved.</p>
</footer>

<script>
function updateQty(input, price) {
    const qty = parseInt(input.value);
    const orderId = input.dataset.order;
    const total = (qty * price).toFixed(2);
    document.getElementById('total-' + orderId).textContent = total + ' SAR';

    // Update in DB via AJAX (simple form submit approach)
    fetch('update_qty.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `order_id=${orderId}&quantity=${qty}&total=${total}`
    });
}
</script>

</body>
</html>
