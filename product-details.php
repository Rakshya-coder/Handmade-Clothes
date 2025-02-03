<?php
session_start();
require 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the product ID is in the URL
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Fetch product details from the database
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if product exists
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        die("Product not found.");
    }
} else {
    die("No product ID provided.");
}

// Handle Add to Cart functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $selected_size = $_POST['size'];
    $product_id = $_POST['product_id'];

    // Initialize cart if not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Create a unique cart key using both product ID and size to differentiate between sizes
    $cart_key = $product_id . '_' . $selected_size;

    // Add product with selected size to the cart
    if (isset($_SESSION['cart'][$cart_key])) {
        $_SESSION['cart'][$cart_key]['quantity']++;
    } else {
        $_SESSION['cart'][$cart_key] = [
            'quantity' => 1,
            'size' => $selected_size,
            'product_id' => $product_id
        ];
    }

    // Redirect with success message
    header('Location: cart.php?message=' . urlencode('Product added to cart!'));
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/detail.css">
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
            <li><a href="logout.php">Logout</a></li>

        </ul>
    </nav>

    <div class="container">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        <p><?php echo htmlspecialchars($product['description']); ?></p>
        <p>Price:Rs <?php echo htmlspecialchars($product['price']); ?></p>
        <p>Available Stock: <?php echo htmlspecialchars($product['stock']); ?></p>

        <form method="POST" action="">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
            <label for="size">Select Size:</label>
            <select name="size" id="size" required>
                <?php
                if (!empty($product['sizes'])) {
                    $sizes = explode(',', $product['sizes']);
                    foreach ($sizes as $size) {
                        echo "<option value='" . htmlspecialchars(trim($size)) . "'>" . htmlspecialchars(trim($size)) . "</option>";
                    }
                } else {
                    echo "<option value=''>No sizes available</option>";
                }
                ?>
            </select>
            <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>

        <!-- Back to Products Button -->
        <a href="products.php" class="back-to-products-button">Back to Products</a>
    </div>

    <?php
    if (isset($_GET['message'])) {
        echo "<script>alert('" . htmlspecialchars($_GET['message']) . "');</script>";
    }
    ?>
</body>

</html>