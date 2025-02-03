<?php
session_start();
require 'db.php';


// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize cart and cancel reasons if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (!isset($_SESSION['cancel_reason'])) {
    $_SESSION['cancel_reason'] = [];
}

$cart = $_SESSION['cart'];
$total_price = 0;
$product_names = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);

    if (isset($_POST['edit'])) {
        $quantity = intval($_POST['quantity']);
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    } elseif (isset($_POST['cancel'])) {
        $cancel_reason = trim($_POST['cancel_reason']);
        if (!empty($cancel_reason)) {
            $_SESSION['cancel_reason'][$product_id] = $cancel_reason;
            unset($_SESSION['cart'][$product_id]);
        } else {
            echo "<script>alert('Please provide a reason for cancellation.');</script>";
        }
    }

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cart.css">
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
    <div class='cart-box'>
        <h2>Your Cart</h2>
        <?php if (!empty($cart)): ?>
            <?php
            $placeholders = implode(',', array_fill(0, count($cart), '?'));
            $stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id IN ($placeholders)");
            $stmt->bind_param(str_repeat('i', count($cart)), ...array_keys($cart));
            $stmt->execute();
            $result = $stmt->get_result();
            $products = [];
            while ($row = $result->fetch_assoc()) {
                $products[$row['id']] = $row;
            }
            ?>
            <?php foreach ($cart as $product_id => $quantity): ?>
                <?php if (isset($products[$product_id])): ?>
                    <?php
                    $product = $products[$product_id];
                    $product_price = $product['price'];
                    $total_item_price = $product_price * $quantity;
                    $total_price += $total_item_price;
                    $product_names[] = $product['name'];
                    ?>
                    <div class="cart-item">
                        <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div>
                            <p><strong>Product Name:</strong> <?php echo htmlspecialchars($product['name']); ?></p>
                            <p><strong>Quantity:</strong> <?php echo $quantity; ?></p>
                            <p><strong>Price per Item:</strong> Rs <?php echo $product_price; ?></p>
                            <p><strong>Total Price:</strong> Rs <?php echo $total_item_price; ?></p>
                            <form method="post" action="">
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                <input type="number" name="quantity" value="<?php echo $quantity; ?>" min="1">
                                <button type="submit" name="edit">Edit</button>
                                <textarea name="cancel_reason" placeholder="Reason for cancellation"></textarea>
                                <button type="submit" name="cancel" style="background-color: red;">Cancel</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <h3>Total Price: Rs <?php echo $total_price; ?></h3>
            <?php
            $_SESSION['total_price'] = $total_price;
            $_SESSION['product_names'] = implode(", ", $product_names);
            ?>
            <form action="checkout.php" method="POST">
                <button type="submit" style="background-color: #A0522D;">Proceed to Checkout</button>
            </form>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
</body>

</html>