<?php
session_start();
require_once "db.php";

$error = "";
$usernameError = $passwordError = "";
$username = $password = "";

// Redirect if already logged in as admin
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) {
    header("Location: admin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $validated = true;

    if (empty($_POST["username"])) {
        $usernameError = "Username is required";
        $validated = false;
    } else {
        $username = $_POST["username"];
    }

    if (empty($_POST["password"])) {
        $passwordError = "Password is required";
        $validated = false;
    } else {
        $password = $_POST["password"];
    }

    if ($validated) {
        $sql = "SELECT * FROM admin WHERE username = '$username'";
        $result = mysqli_query($connection, $sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            // Simple password check (admin passwords stored as plain text in your DB)
            if ($row['password'] === $password) {
                $_SESSION['loggedin'] = true;
                $_SESSION['isAdmin']  = 1;
                $_SESSION['name']     = $row['username'];
                $_SESSION['id']       = $row['id'];
                header("Location: admin.php");
                exit();
            } else {
                $error = "Invalid username or password";
            }
        } else {
            $error = "Invalid username or password";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - StyleX Furniture</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <span class="logo">🪑 StyleX Furniture</span>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="login.php">Customer Login</a></li>
        <li><a href="register.php">Register</a></li>
    </ul>
</nav>

<div class="hero-section" style="height:180px;">
    <h1 style="font-size:36px;">🔐 Admin Login</h1>
</div>

<div class="form-container">
    <h2>Admin Sign In</h2>

    <?php if (!empty($error)) { ?>
        <p class="error-msg" style="text-align:center; font-size:14px; margin-bottom:10px;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="username">Admin Username</label>
        <input type="text" name="username" id="username" placeholder="Admin username" value="<?php echo $username; ?>">
        <p class="error-msg"><?php echo $usernameError; ?></p>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="password">
        <p class="error-msg"><?php echo $passwordError; ?></p>

        <button type="submit">Login</button>
    </form>

    <div class="form-links">
        <p>Not an admin? <a href="login.php">Login as Customer</a></p>
    </div>
</div>

<footer>
    <p>📱 Instagram | ✉️ Email: info@stylexfurniture.com</p>
    <p>&copy; 2025 StyleX Furniture. All rights reserved.</p>
</footer>

</body>
</html>
