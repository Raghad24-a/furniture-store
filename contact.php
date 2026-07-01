<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Help & Support - StyleX Furniture</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <span class="logo">🪑 StyleX Furniture</span>
    <ul>
        <li><a href="home.php">Home</a></li>
<?php
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1) { ?>
        <li><a href="admin.php">Dashboard</a></li>
<?php   } else { ?>
        <li><a href="checkout.php">Current Order</a></li>
        <li><a href="orders.php">All Orders</a></li>
<?php   } ?>
        <li><a href="logout.php">Log out</a></li>
<?php } else { ?>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
<?php } ?>
        <li><a href="contact.php" class="active">Help & Support</a></li>
    </ul>
</nav>

<div class="hero-section" style="height:180px;">
    <h1 style="font-size:36px;">❓ Help & Support</h1>
    <p>We're here to help you</p>
</div>

<!-- FAQ SECTION -->
<div class="faq-section">
    <h2>❓ Frequently Asked Questions</h2>

    <div class="faq-item">
        <h3>📦 How can I track my order?</h3>
        <p>Once your order is shipped, you'll receive an email with the tracking details.</p>
    </div>

    <div class="faq-item">
        <h3>💳 What payment methods do you accept?</h3>
        <p>We accept credit cards, debit cards, and cash on delivery.</p>
    </div>

    <div class="faq-item">
        <h3>🔄 Can I return or exchange my order?</h3>
        <p>Yes, you can return your order within 7 days of delivery in original condition.</p>
    </div>

    <div class="faq-item">
        <h3>🚚 How long does delivery take?</h3>
        <p>Standard delivery takes 3–5 business days within Saudi Arabia.</p>
    </div>

    <div class="faq-item">
        <h3>🛋️ Do you offer assembly services?</h3>
        <p>Yes! We offer professional assembly for an additional fee. Contact us to schedule.</p>
    </div>
</div>

<!-- CONTACT SECTION -->
<div class="contact-section">
    <h2>📩 Contact Us</h2>
    <form>
        <label for="cname">Name</label>
        <input type="text" id="cname" name="name" placeholder="Your Name" required>

        <label for="cemail">Email</label>
        <input type="email" id="cemail" name="email" placeholder="your@email.com" required>

        <label for="cmessage">Message</label>
        <textarea id="cmessage" name="message" rows="4" placeholder="How can we help you?" required></textarea>

        <button type="button" onclick="alert('✅ Message sent! We will contact you soon.')">Send Message</button>
    </form>
</div>

<footer>
    <p>📱 Instagram | ✉️ Email: info@stylexfurniture.com | ☎ Phone: (+966) 011-4353-545</p>
    <p>&copy; 2025 StyleX Furniture. All rights reserved.</p>
</footer>

</body>
</html>
