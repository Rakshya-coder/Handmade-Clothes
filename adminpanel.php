<?php
// Database connection
session_start();
require 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['AdminloginId'])) {
    header("location: admin_login.php");
    exit;
}

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products
$sql = "SELECT * FROM products";
$productResult = $conn->query($sql);

// Fetch all reviews
$sql_reviews = "
    SELECT 
        reviews.id AS review_id,
        users.name AS user_name,
        products.name AS product_name,
        reviews.rating,
        reviews.comment,
        reviews.created_at
    FROM 
        reviews
    JOIN 
        products ON reviews.product_id = products.id
    JOIN 
        users ON reviews.user_id = users.id
    ORDER BY 
        reviews.created_at DESC";
$reviewResult = $conn->query($sql_reviews);

// Fetch users
$sql_users = "SELECT * FROM users";
$userResult = $conn->query($sql_users);

// Fetch cart data
$sql_cart = "
    SELECT 
        cart.id AS cart_id,
        users.name,
        products.name AS product_name,
        cart.quantity,
        cart.added_at
    FROM 
        cart
    JOIN 
        products ON cart.product_id = products.id
    JOIN 
        users ON cart.user_id = users.id";
$cartResult = $conn->query($sql_cart);

// Check for query errors
if (!$productResult || !$userResult || !$cartResult || !$reviewResult) {
    die("Query failed: " . $conn->error);
}

// Get total user count
$sql_user_count = "SELECT COUNT(*) AS total_users FROM users";
$userCountResult = $conn->query($sql_user_count);
$userCount = $userCountResult->fetch_assoc()['total_users'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Products</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<script>
    function showSection(sectionId) {
        const sections = document.querySelectorAll('.container');
        sections.forEach(section => section.style.display = 'none');
        document.getElementById(sectionId).style.display = 'block';
    }
</script>

<body onload="showSection('dashboard')">
    <nav>
        <div class="logo">
            <img src="logo.jpg" alt="Logo">
        </div>
        <h1>Admin Panel</h1>
        <div>
            <button onclick="window.location.href='logout.php';">Logout</button>
        </div>
    </nav>

    <div class="sidebar">
        <a onclick="showSection('dashboard')">Dashboard</a>
        <a onclick="showSection('User_info')">User Information</a>
        <a onclick="showSection('product_management')">Product Management</a>
        <a onclick="showSection('Ordres')">View Orders</a>
        <a onclick="showSection('Reviews')">View Reviews</a>
    </div>

    <div id="dashboard" class="container">
        <h2>Dashboard</h2>

        <div class="products-overview">
            <h4>Available Products</h4>
            <div class="products-container">
                <?php
                $sql = "SELECT name, price FROM products";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $totalProducts = $result->num_rows;
                    echo "<p>Total products: " . $totalProducts . "</p>";

                    echo "<table border='1' cellspacing='0' cellpadding='10'>";
                    echo "<thead>";
                    echo "<tr><th>Product Name</th><th>Price</th></tr>";
                    echo "</thead>";
                    echo "<tbody>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                        echo "<td>Rs " . number_format($row["price"]) . "</td>";
                        echo "</tr>";
                    }

                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "<p>No results</p>";
                }
                ?>

                <div>
                    <h3><i class="fas fa-user"></i>Total Users: <?php echo $userCount; ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div id="User_info" class="container">
        <h2>User Information</h2>
        <div class="table-container">
            <table>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Password</th>
                </tr>
                <?php while ($row = $userResult->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['password']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

    <div id="product_management" class="container">
        <h2>Manage Products</h2>
        <div class="table-container">
            <a href="add_product.php"><button>New Product</button></a>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($productResult->num_rows > 0) {
                        while ($row = $productResult->fetch_assoc()) {
                            // Sanitize the output to prevent XSS
                            $productId = htmlspecialchars($row["id"]);
                            $productName = htmlspecialchars($row["name"]);
                            $productDescription = htmlspecialchars($row["description"]);
                            $productPrice = htmlspecialchars($row["price"]);
                            $productImage = htmlspecialchars($row["image"]);

                            echo "<tr>";
                            echo "<td>{$productId}</td>";
                            echo "<td>{$productName}</td>";
                            echo "<td>{$productDescription}</td>";
                            echo "<td>Rs {$productPrice}</td>";
                            echo "<td><img src='images/" . $row["image"] . "' alt='" . $row["name"] . "' style='width: 50px; height: 50px;'></td>";
                            echo "<td>
                                <a href='edit_product.php?id={$productId}'>Edit</a> | 
                                <a href='delete_product.php?id={$productId}' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No products found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="Ordres" class="container">
        <h2>View Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Username</th>
                    <th>Quantity</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($cartResult->num_rows > 0) {
                    while ($row = $cartResult->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["cart_id"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["product_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["quantity"]) . "</td>";
                        echo "<td>" . htmlspecialchars(date("Y-m-d H:i:s", strtotime($row["added_at"]))) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No orders found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div id="Reviews" class="container">
        <h2>View Reviews</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Username</th>
                        <th>Rating</th>
                        <th>Comment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($reviewResult->num_rows > 0) {
                        while ($row = $reviewResult->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["review_id"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["product_name"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["user_name"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["rating"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["comment"]) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No reviews found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>