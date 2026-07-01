<?php
session_start();
require_once "db.php";

// Check if the admin is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin'] === true) {
    header('Location: admin_login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    if (empty($id)) {
        $validated = false;
    } else {
        $sql = "DELETE FROM products WHERE id = '$id'";
        $result = mysqli_query($connection, $sql);
        header('Location: items_list.php');
        exit();
    }
}
?>
