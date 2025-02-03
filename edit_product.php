<?php
session_start();
require 'db.php';
if (!isset($_SESSION['AdminloginId'])) {
    header("location: admin_login.php");
    exit;
}

// Check if 'id' is set and is a valid integer
if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id = (int)$_GET["id"];

    // Prepare and execute the SQL statement to get product details
    $stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        die("Product not found.");
    }
    $stmt->close();
} else {
    die("Invalid ID.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $name = $conn->real_escape_string(trim($_POST["name"]));
    $description = $conn->real_escape_string(trim($_POST["description"]));
    $price = filter_var($_POST["price"], FILTER_VALIDATE_FLOAT);
    $image = $_FILES["image"]["name"];

    if ($price === false) {
        die("Invalid price format.");
    }

    $target_dir = "../images/";
    $target_file = $target_dir . basename($image);

    // Initialize the SQL query for updating the product
    $update_sql = "UPDATE products SET name=?, description=?, price=?";
    $params = [$name, $description, $price];

    if ($image) {
        if ($_FILES["image"]["error"] === UPLOAD_ERR_OK) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $update_sql .= ", image=?";
                $params[] = $image;
            } else {
                die("Sorry, there was an error uploading your file.");
            }
        } else {
            die("File upload error: " . $_FILES["image"]["error"]);
        }
    }

    $update_sql .= " WHERE id=?";
    $params[] = $id;

    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param(str_repeat("s", count($params) - 1) . "i", ...$params);

    if ($stmt->execute()) {
        echo "Product updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
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
        <h2 style="text-align: left;">Edit Product</h2>
        <form action="edit_product.php?id=<?php echo htmlspecialchars($id); ?>" method="post" enctype="multipart/form-data">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required>

            <!-- <label for="image">Image:</label>
            <input type="file" id="image" name="image">
            <p>Current Image: <?php echo htmlspecialchars($product['image']); ?></p> -->

            <button type="submit">Update Product</button>
        </form>
        <div style="text-align: left; margin-top: 10px;">
            <button onclick="window.location.href='adminpanel.php';">Back</button>
        </div>
    </div>
</body>

</html>