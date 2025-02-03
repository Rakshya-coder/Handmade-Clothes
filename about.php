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
    <title>Handmade cloth</title>
    <link rel="stylesheet" href="css/conabot.css" />
    <link rel="stylesheet" href="css/style.css"> <!-- Link to your CSS file -->
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
    <section class="about-section">
        <h1>About Us</h1>
        <div class="about-links">
            <p><a href="home.php">home</a> /about</p>
        </div>
    </section>
    <section class="about-content">
        <div class="about-container">
            <div class="about-img">
                <img src="about.jpg" alt="pic">
            </div>
            <div class="abt-parag">
                <p class="txt-big">Welcome to Handmade Clothes! </p>
                <p class="txt-small">
                    We’re dedicated to offering you beautiful, handmade garments that stand out from the rest.Skilled artisans have taken great care and attention to detail in creating every piece in our collection. In addition to purchasing distinctive apparel from us, you are also promoting traditional craftsmanship. Look through our selection to get the ideal, unique outfit right now!
                     
                     
                </p>

            </div>
        </div>
    </section>
</body>

</html>