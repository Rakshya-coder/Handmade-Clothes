<?php
session_start();
require 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get product ID from the query string
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

// Fetch reviews for the given product
$sql = "
    SELECT 
        reviews.id AS review_id,
        users.name,
        reviews.rating,
        reviews.comment,
        reviews.created_at
    FROM 
        reviews
    JOIN 
        users ON reviews.user_id = users.id
    WHERE 
        reviews.product_id = ?
    ORDER BY 
        reviews.created_at DESC
";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    $user_id = 1; // Assuming a logged-in user with ID 1 for demonstration
    $rating = intval($_POST['rating']);
    $comment = $_POST['comment'];
    $created_at = date("Y-m-d H:i:s");

    $insert_sql = "
        INSERT INTO reviews (product_id, user_id, rating, comment, created_at)
        VALUES (?, ?, ?, ?, ?)
    ";
    $insert_stmt = $conn->prepare($insert_sql);

    if (!$insert_stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $insert_stmt->bind_param("iiiss", $product_id, $user_id, $rating, $comment, $created_at);
    if (!$insert_stmt->execute()) {
        die("Error inserting review: " . $conn->error);
    }
    // Redirect to avoid form resubmission
    header("Location: product_reviews.php?product_id=" . $product_id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Reviews</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Link to your CSS file -->
    <!-- <link rel="stylesheet" href="css/product.css"> Link to your CSS file -->
    <style>
        /* Center the review container and set its width to small */
        .container {
            width: 40%;
            margin: 0 auto;
            /* Center the container horizontally */
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        h3 {
            text-align: left;
            margin-bottom: 30px;
            color: black;
        }

        button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        #review-form {
            margin: 20px auto;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            width: 25%;
            /* Make the form width a bit smaller inside the container */
        }

        #review-form label,
        #review-form input,
        #review-form textarea {
            display: block;
            width: 100%;
            margin-bottom: 0px;
        }

        #review-form button {
            width: auto;
        }

        .review-container {
            margin-top: 30px;
        }

        .review-container div {
            margin-bottom: 10px;
        }

        .review-container h3 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .review-container p {
            margin-bottom: 5px;
        }

        .review-container small {
            color: #888;
        }

        button {
            display: block;
            margin: 15px auto;
            padding: 10px 10px;
            background-color: #653C2E;
            /* Updated background color */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #4b2d21;
            /* A darker shade for hover effect */
        }
    </style>
    <script>
        // JavaScript function to toggle the review form
        function toggleReviewForm() {
            var form = document.getElementById('review-form');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
    </script>
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
            <li><a href="cart.php">Cart</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <?php
    // Assuming the product_id is passed as a GET parameter
    $product_id = $_GET['product_id'];

    // Fetch the product name
    $query = "SELECT name FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($product_name);
    $stmt->fetch();
    $stmt->close();

    // Close the database connection
    ?>
    <h2>Reviews Product: <?php echo htmlspecialchars($product_name); ?></h2>

    <button onclick="toggleReviewForm()">Write Reviews</button>

    <form id="review-form" method="post" action="">
        <h2>Submit a Review</h2>
        <label for="rating">
            <h3>Rating (1-5):</h3>
        </label>
        <input type="number" name="rating" id="rating" min="1" max="5" required>
        <br>
        <label for="comment">
            <h3>Comment:</h3>
        </label>
        <textarea name="comment" id="comment" rows="4" required></textarea>
        <br>
        <button type="submit">Submit Review</button>
    </form>

    <div class="review-container">
        <h2>Reviews:</h2>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div>";
                echo "<h3>" . htmlspecialchars($row["name"]) . " (Rating: " . htmlspecialchars($row["rating"]) . ")</h3>";
                echo "<p>" . htmlspecialchars($row["comment"]) . "</p>";
                echo "<small>Posted on " . htmlspecialchars($row["created_at"]) . "</small>";
                echo "</div><hr>";
            }
        } else {
            echo "<p>No reviews yet.</p>";
        }
        ?>
    </div>

    <!-- Back button -->
    <button onclick="window.location.href='products.php';">Back to Products</button>

</body>

</html>

<?php
// Close the database connection
$conn->close();
?>