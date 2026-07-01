<?php
session_start();
if (session_destroy()) {
    // session destroyed, show logout page
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log Out - StyleX Furniture</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <span class="logo">🪑 StyleX Furniture</span>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
    </ul>
</nav>

<div class="logout-message">
    <h2>🪑 You have been logged out successfully</h2>
    <p>We hope to see you again soon!</p>
    <a href="home.php" class="btn-primary">Return to Home</a>
</div>

<footer>
    <p>📱 Instagram | ✉️ Email: info@stylexfurniture.com</p>
    <p>&copy; 2025 StyleX Furniture. All rights reserved.</p>
</footer>

<script>
    // Clear session on client side
    setTimeout(() => { window.location.href = "home.php"; }, 3000);
</script>

</body>
</html>
