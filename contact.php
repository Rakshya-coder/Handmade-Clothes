<?php
// Start the session
session_start();
require 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Handmade Cloth</title>
    <link rel="stylesheet" href="css/style.css" /> <!-- Link to your CSS file -->
    <link rel="stylesheet" href="css/conabot.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <nav>
        <div class="logo">
            <img src="logo.jpg" alt="Logo">
        </div>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="cart.php"><i style="font-size:20px" class="fa">&#xf07a;</i> Cart</a></li>
            <li><a href="view_reviews.php">All Reviews</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <section class="contact-section">
        <h1>Contact Us</h1>

    </section>
    <div class="contact-container">
        <div class="contact-left">
            <div class="contact-form">
                <h2>Get in Touch</h2>
                <form action="send_contact.php" method="post">
                    <input type="text" name="name" placeholder="Your Name" required>
                    <input type="email" name="email" placeholder="Your Email" required>
                    <input type="text" name="subject" placeholder="Subject" required>
                    <textarea name="message" placeholder="Your Message" required></textarea>
                    <button type="submit">Send Message</button>
                </form>
            </div>
        </div>
        <div class="contact-right contact-details">
            <h2>Contact Details</h2>
            <p><i class="fa fa-home"></i> 123 Street, Nepal</p>
            <p><i class="fa fa-phone"></i> +977 9876541231</p>
            <p><i class="fa fa-envelope"></i> handmade019@gmail.com</p>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/js/fontawesome.min.js"></script>
    </

        </body>

</html>