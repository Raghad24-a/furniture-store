<?php
// remove_order.php
session_start();
require_once "db.php";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $sql = "DELETE FROM orders WHERE order_id = '$order_id'";
    mysqli_query($connection, $sql);
}
header("Location: checkout.php");
exit();
?>
