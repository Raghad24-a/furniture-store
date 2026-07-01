<?php
session_start();
require_once "db.php";

// Redirect if already logged in as admin
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) {
    header("Location: admin.php");
    exit();
}

$nameError = $passwordError = "";
$name = $password = "";
$validated = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['name'])) {
        $nameError = "Name is required";
        $validated = false;
    } else {
        $name = $_POST['name'];
    }
    if (empty($_POST['password'])) {
        $passwordError = "Password is required";
        $validated = false;
    } else {
        $password = $_POST['password'];
    }

    if ($validated) {
        // Admin passwords stored as plain text (matching your DB structure)
        $sql = "INSERT INTO admin (username, password) VALUES ('$name', '$password')";
        if (mysqli_query($connection, $sql) == true) {
            header("Location: admin_login.php");
            exit();
        } else {
            die("Something went wrong. Please try again later.");
        }
    }
    mysqli_close($connection);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Register - StyleX Furniture</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <span class="logo">🪑 StyleX Furniture</span>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="login.php">Customer Login</a></li>
        <li><a href="admin_login.php">Admin Login</a></li>
    </ul>
</nav>

<div class="hero-section" style="height:180px;">
    <h1 style="font-size:36px;">🔐 Admin Register</h1>
</div>

<div class="form-container">
    <h2>Admin Sign Up</h2>

    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="name">Admin Username</label>
        <input type="text" name="name" id="name" placeholder="AdminName" value="<?php echo $name; ?>">
        <p class="error-msg"><?php echo $nameError; ?></p>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="password">
        <p class="error-msg"><?php echo $passwordError; ?></p>

        <button type="submit">Sign Up</button>
    </form>

    <div class="form-links">
        <p>Already have an account? <a href="admin_login.php">Login Here</a></p>
        <p>Register as User? <a href="register.php">Register</a></p>
    </div>
</div>

<footer>
    <p>📱 Instagram | ✉️ Email: info@stylexfurniture.com</p>
    <p>&copy; 2025 StyleX Furniture. All rights reserved.</p>
</footer>

</body>
</html>
