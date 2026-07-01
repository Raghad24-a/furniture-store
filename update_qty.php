<?php
// update_qty.php
session_start();
require_once "db.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $quantity  = intval($_POST['quantity']);
    $total     = floatval($_POST['total']);
    $sql = "UPDATE orders SET quantity='$quantity', total_price='$total' WHERE order_id='$order_id'";
    mysqli_query($connection, $sql);
    echo "ok";
}
?>
