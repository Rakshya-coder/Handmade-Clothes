<?php
session_start();
require 'db.php';



if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // Delete the product image from the server
    $sql = "SELECT image FROM products WHERE id=$id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image = $row['image'];
        $imagePath = "../images/" . $image;
        if (file_exists($imagePath)) {
            unlink($imagePath); // Delete the image file
        }
    }

    // Delete the product from the database
    $sql = "DELETE FROM products WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Product deleted successfully";
    } else {
        echo "Error deleting product: " . $conn->error;
    }
}

$conn->close();

// Redirect back to the admin panel
header("Location: adminpanel.php");
exit();
