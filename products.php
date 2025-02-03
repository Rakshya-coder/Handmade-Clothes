<?php
session_start();
require 'db.php';


// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];

    // Initialize cart if not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the product is already in the cart and its value is numeric
    if (isset($_SESSION['cart'][$product_id])) {
        // Ensure that the current cart quantity is a numeric value
        if (is_numeric($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++;
        } else {
            // Reset to 1 if somehow it's not a number
            $_SESSION['cart'][$product_id] = 1;
        }
    } else {
        $_SESSION['cart'][$product_id] = 1; // Initialize with quantity 1
    }

    // Redirect to the same page with a success message
    header('Location: cart.php?message=' . urlencode('Product added to cart!'));
    exit();
}


// Fetch products from the database
$sql = "SELECT id, name, description, price, image FROM products";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Link to your CSS file -->
    <link rel="stylesheet" href="css/product.css">
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

    <h1 style="text-align: center;">Available Products</h1>
    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='product'>";
                // Wrap product image with a link to product details page without target="_blank"
                echo "<a href='product-details.php?id=" . htmlspecialchars($row["id"]) . "'>";
                echo "<img src='images/" . htmlspecialchars($row["image"]) . "' alt='" . htmlspecialchars($row["name"]) . "'>";
                echo "</a>";
                echo "<h2>" . htmlspecialchars($row["name"]) . "</h2>";
                echo "<p>" . htmlspecialchars($row["description"]) . "</p>";
                echo "<span class='price'>Rs " . htmlspecialchars($row["price"]) . "</span>";
                echo "<form method='POST' action=''>";
                echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($row["id"]) . "'>";
                echo "<button type='submit' name='add_to_cart'>Add to Cart</button>";
                echo "</form> <br>";
                echo "<a href='product_reviews.php?product_id=" . htmlspecialchars($row["id"]) . "' class='review-button'><button>Review</button></a>";
                echo "</div>";
            }
        } else {
            echo "No products available.";
        }
        $conn->close();
        ?>
    </div>

    <?php
    if (isset($_GET['message'])) {
        echo "<script>alert('" . htmlspecialchars($_GET['message']) . "');</script>";
    }
    ?>
</body>

</html>