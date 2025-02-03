<?php
session_start();

// Ensure cart data is available
if (!isset($_SESSION['total_price']) || !isset($_SESSION['product_names'])) {
    die("No cart data found. Please add items to the cart before proceeding to checkout.");
}

$total_price = $_SESSION['total_price'];
$product_names = $_SESSION['product_names'];

?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/checkout.css">
</head>

<body>
    <div class="checkout-container">
        <h2>Checkout</h2>
        <form action="payment-request.php" method="POST">

            <input type="hidden" id="amount" name="amount" value="<?php echo $total_price; ?>">
            <input type="hidden" id="purchase_id" name="purchase_id" value="<?php echo uniqid(); ?>">
            <input type="hidden" id="product_name" name="product_name" value="<?php echo $product_names; ?>">

            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter your full name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="phone">Phone:</label>
            <input type="number" id="phone" name="phone" placeholder="Enter your phone" required>

            <button name="submit" type="submit">Pay with Khalti</button>
        </form>
    </div>
</body>

</html>