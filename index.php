<?php
require("db.php");

$sql = "SELECT id, name, description, price, image FROM products LIMIT 4";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My E-Commerce Website</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">



</head>

<body>
    <nav>
        <div class="logo">
            <img src="logo.jpg" alt="Logo">
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li class="profile-dropdown">
                <button class="profile-dropbtn">
                    <i class="fas fa-user"></i> Profile</button>
                <div class="profile-dropdown-content">
                    <a href="admin_login.php">Admin</a>
                    <a href="login.php">User</a>
                </div>
            </li>
        </ul>
    </nav>



    <section class="featured-products">
        <h2>Featured Products</h2>
        <div class="products-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="product-item">
                        <img src="images/<?php echo htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="product-details">
                            <h3><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p><?php echo htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="price">Rs <?php echo htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products available.</p>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <div class="contact-right contact-details">
            <h2>Contact Details</h2>
            <p><i class="fa fa-home"></i> 123 Street, Nepal</p>
            <p><i class="fa fa-phone"></i> +977 9876541231</p>
            <p><i class="fa fa-envelope"></i> handmade019@gmail.com</p>
        </div>
        <div class="footer-section">

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