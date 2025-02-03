<?php
// Start the session
session_start();
require 'db.php';

// // Check if the user is logged in
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reviews</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/admin.css">
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