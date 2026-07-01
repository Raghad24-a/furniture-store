<?php
session_start();
require_once "db.php";

// Redirect if already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: home.php");
    exit();
}

$emailError = $passwordError = $nameError = $phoneError = "";
$name = $email = $password = $phone = $address = "";
$validated = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty($_POST['name'])) {
        $nameError = "Name is required";
        $validated = false;
    } else {
        $name = $_POST['name'];
    }
    // Validate email
    if (empty($_POST['email'])) {
        $emailError = "Email is required";
        $validated = false;
    } else {
        $email = $_POST['email'];
    }
    // Validate password
    if (empty($_POST['password'])) {
        $passwordError = "Password is required";
        $validated = false;
    } else {
        $password = $_POST['password'];
    }

    $phone   = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';

    if ($validated) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        // Check for duplicate email
        $check = "SELECT * FROM users WHERE email = '$email'";
        $chkRes = mysqli_query($connection, $check);
        if (mysqli_num_rows($chkRes) > 0) {
            $emailError = "Email already registered";
        } else {
            $sql = "INSERT INTO users (full_name, username, email, password, address, phone) VALUES ('$name', '$name', '$email', '$hashed', '$address', '$phone')";
            if (mysqli_query($connection, $sql) == true) {
                header("Location: login.php");
                exit();
            } else {
                die("Something went wrong. Please try again later.");
            }
        }
    }
    mysqli_close($connection);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - StyleX Furniture</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <span class="logo">🪑 StyleX Furniture</span>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php" class="active">Register</a></li>
        <li><a href="contact.php">Help & Support</a></li>
    </ul>
</nav>

<div class="hero-section" style="height:180px;">
    <h1 style="font-size:36px;">Create Account</h1>
</div>

<div class="form-container">
    <h2>Sign Up</h2>

    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="name">Full Name</label>
        <input type="text" name="name" id="name" placeholder="Sara" value="<?php echo $name; ?>">
        <p class="error-msg"><?php echo $nameError; ?></p>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="mail@address.com" value="<?php echo $email; ?>">
        <p class="error-msg"><?php echo $emailError; ?></p>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="password">
        <p class="error-msg"><?php echo $passwordError; ?></p>

        <label for="phone">Phone (Optional)</label>
        <input type="text" name="phone" id="phone" placeholder="05XXXXXXXX" value="<?php echo $phone; ?>">

        <label for="address">Address (Optional)</label>
        <input type="text" name="address" id="address" placeholder="City, Street" value="<?php echo $address; ?>">

        <button type="submit">Register</button>
    </form>

    <div class="form-links">
        <p>Already have an account? <a href="login.php">Login as Customer</a></p>
        <p>Register as Admin? <a href="admin_register.php">Register as Admin</a></p>
    </div>
</div>

<footer>
    <p>📱 Instagram | ✉️ Email: info@stylexfurniture.com</p>
    <p>&copy; 2025 StyleX Furniture. All rights reserved.</p>
</footer>

</body>
</html>
