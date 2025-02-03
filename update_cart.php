<?php
session_start();

// Include the database connection file
require 'db.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
    $action = isset($_POST['action']) ? $_POST['action'] : null;

    // Get the current cart from the session
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

    if ($product_id && $action) {
        if ($action === 'Update') {
            $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
            if ($quantity > 0) {
                // Update the quantity in the cart
                $cart[$product_id] = $quantity;
            }
        } elseif ($action === 'Remove') {
            // Remove the item from the cart
            unset($cart[$product_id]);
        }

        // Save the updated cart back to the session
        $_SESSION['cart'] = $cart;
    }

    // Redirect back to the cart page
    header('Location: cart.php');
    exit();
} else {
    // If the request method is not POST, redirect to cart page
    header('Location: cart.php');
    exit();
}
