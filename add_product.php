<?php
session_start();
require 'db.php';

if (!isset($_SESSION['AdminloginId'])) {
    header("location: admin_login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $stock = $_POST["stock"]; // Get the stock from the form
    $image = $_FILES["image"]["name"];

    $target_dir = "images/";
    $target_file = $target_dir . basename($image);


    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $sql = "INSERT INTO products (name, description, price, stock, image) VALUES ('$name', '$description', '$price', '$stock', '$image')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('New product added successfully');</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "<script>alert('Image upload unsuccessful');</script>";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>
    <nav>
        <div class="logo">
            <img src="logo.jpg" alt="Logo">
        </div>
        <h1> Admin Panel</h1>
        <div>
            <button onclick="window.location.href='logout.php';">Logout</button>
        </div>
    </nav>
    <div class="container">
        <h2 style="text-align: left;">Add New Product</h2>
        <form action="add_product.php" method="post" enctype="multipart/form-data">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" min="0" required>

            <label for="image">Image:</label>
            <input type="file" id="image" name="image" required>
            <br>
            <button type="submit">Add Product</button>
        </form>

        <div style="text-align: left; margin-top: 10px;">
            <button onclick="window.location.href='adminpanel.php';">Back </button>
        </div>
    </div>
</body>

</html>