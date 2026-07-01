<?php
session_start();
require_once "db.php";

$validated = true;
$error = "";
$emailError = $passwordError = "";
$email = $password = "";

// Redirect if already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) {
        header("Location: admin.php");
    } else {
        header("Location: home.php");
    }
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty($_POST["email"])) {
        $emailError = "Email is required";
        $validated = false;
    } else {
        $email = $_POST["email"];
    }
    // Validate password
    if (empty($_POST["password"])) {
        $passwordError = "Password is required";
        $validated = false;
    } else {
        $password = $_POST["password"];
    }

    if ($validated === true) {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($connection, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                if (password_verify($password, $row['password'])) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['id']       = $row['id'];
                    $_SESSION['name']     = $row['full_name'];
                    $_SESSION['email']    = $row['email'];
                    $_SESSION['isAdmin']  = $row['isAdmin'];

                    if ($row['isAdmin'] == 1) {
                        header("Location: admin.php");
                    } else {
                        header("Location: home.php");
                    }
                    exit();
                } else {
                    $error = "Your Email or Password is invalid";
                }
            }
        } else {
            $error = "Your Email or Password is invalid";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - StyleX Furniture</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <span class="logo">🪑 StyleX Furniture</span>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="login.php" class="active">Login</a></li>
        <li><a href="register.php">Register</a></li>
        <li><a href="contact.php">Help & Support</a></li>
    </ul>
</nav>

<div class="hero-section" style="height:180px;">
    <h1 style="font-size:36px;">Customer Login</h1>
</div>

<div class="form-container">
    <h2>Sign In</h2>

    <?php if (!empty($error)) { ?>
        <p class="error-msg" style="text-align:center; font-size:14px; margin-bottom:10px;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" placeholder="mail@address.com" value="<?php echo $email; ?>">
        <p class="error-msg"><?php echo $emailError; ?></p>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="password">
        <p class="error-msg"><?php echo $passwordError; ?></p>

        <button type="submit">Sign In</button>
    </form>

    <div class="form-links">
        <p>Not registered yet? <a href="register.php">Register Here</a></p>
        <p>Are you an admin? <a href="admin_login.php">Login as Admin</a></p>
    </div>
</div>

<footer>
    <p>📱 Instagram | ✉️ Email: info@stylexfurniture.com</p>
    <p>&copy; 2025 StyleX Furniture. All rights reserved.</p>
</footer>

</body>
</html>
