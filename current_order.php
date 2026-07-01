<?php
session_start();
require_once "db.php";

/* تأكد أن المستخدم مسجل دخول */
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

/* تأكد أن رقم المنتج موجود */
if (!isset($_POST['id']) || empty($_POST['id'])) {
    header("Location: items_list.php");
    exit();
}

$username   = $_SESSION['name'];
$product_id = intval($_POST['id']);
$quantity   = 1;

/* جلب user_id من جدول users */
$userSql = "SELECT id FROM users WHERE username = '$username'";
$userResult = mysqli_query($connection, $userSql);
$user = mysqli_fetch_assoc($userResult);

if (!$user) {
    die("Error: User not found.");
}

$user_id = $user['id'];

/* جلب سعر المنتج */
$productSql = "SELECT price, name FROM products WHERE id = '$product_id'";
$productResult = mysqli_query($connection, $productSql);
$product = mysqli_fetch_assoc($productResult);

if (!$product) {
    die("Error: Product not found.");
}

$total_price = $product['price'] * $quantity;

/* إضافة الطلب */
$insertSql = "INSERT INTO orders (user_id, product_id, quantity, total_price)
              VALUES ('$user_id', '$product_id', '$quantity', '$total_price')";
mysqli_query($connection, $insertSql);

/* التحويل لصفحة السلة */
header("Location: checkout.php");
exit();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Adding to Cart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <!-- هذه الصفحة لا تُعرض، يتم التحويل فورًا -->
</body>
</html>