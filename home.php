<?php
// Start the session
session_start();
require 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


// Fetch featured products from the database
$sql = "SELECT id, name, description, price, image FROM products LIMIT 4";
$result = $conn->query($sql);
?>
<style>
    body {
        position: relative;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        /* Prevents horizontal scrolling */
    }

    .body-image {
        position: absolute;
        top: 0;
        right: 0;
        /* Change to 'left: 0;' to position on the left */
        max-width: 40%;
        height: auto;
        z-index: -1;
        /* Ensures the image is behind other content */
        opacity: 0.8;
        /* Slight transparency for a softer look */
    }
</style>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My E-Commerce Website</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script>
        // Function to show alerts based on query parameters
        function showAlert() {
            const urlParams = new URLSearchParams(window.location.search);
            const message = urlParams.get('message');
            if (message) {
                alert(decodeURIComponent(message));
                // Clear the message after showing it
                const newUrlParams = new URLSearchParams(window.location.search);
                newUrlParams.delete('message');
                window.history.replaceState(null, '', `${window.location.pathname}?${newUrlParams.toString()}`);
            }
        }

        window.onload = showAlert;
    </script>
</head>

<body>
    <!-- Navigation Bar -->
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

    <section class="backgroundMain">
        <div class="first-section">
            <div class="home-text">
                <p class="text-big">Best Handmade Clothes For Everyone</p>
            </div>
            <div class="home-img">
                <img src="body.jpg" alt="picture">
            </div>
        </div>
    </section>



    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="cart.php">Cart</a></li>
                </ul>
            </div>
            <div class="contact-right contact-details">
                <h2>Contact Details</h2>
                <p><i class="fa fa-home"></i> 123 Street, Nepal</p>
                <p><i class="fa fa-phone"></i> +977 9876541231</p>
                <p><i class="fa fa-envelope"></i> handmade019@gmail.com</p>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="#"><i class="fa fa-facebook"></i></a>
                    <a href="#"><i class="fa fa-twitter"></i></a>
                    <a href="#"><i class="fa fa-instagram"></i></a>
                    <a href="#"><i class="fa fa-linkedin"></i></a>
                </div>
            </div>
        </div>

        <div id="messgae">
            <h2>Welcome to Handmade Clothes!</h2>

            "Discover the charm of handmade fashion—unique designs crafted just for you. Shop now and add a personal touch to your wardrobe!"
            Thank you for visiting!

            Warm regards,
            <h3> The Handmade Clothes Team </h3>
        </div>
    </footer>

</body>

</html>

<?php
$conn->close();
?>