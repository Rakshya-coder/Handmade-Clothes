<?php
// Start the session
session_start();

// Include database connection
require 'db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get email and password from POST data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id, $hashed_password);
    $stmt->fetch();

    // Verify password (assuming it's hashed using password_hash)
    if ($hashed_password && password_verify($password, $hashed_password)) {
        // Password matches, set session variables
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_email'] = $email;

        // Redirect to home.php
        header("Location: home.php");
        exit();
    } else {
        // Invalid credentials, redirect to login with error message
        header("Location: login.php?error=" . urlencode("Invalid credentials"));
        exit();
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css"> <!-- Link your CSS file -->
</head>

<body>
    <h2>Login</h2>

    <form action="login.php" method="POST" class="form">
        <label for="email">Email:</label>
        <input type="email" id="email" placeholder="Enter Your Email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" placeholder="Password" name="password" required><br><br>

        <button type="submit">Login</button>
        <div class="link">
            <a href="signup.php">Don't have an account? <span>Sign up here</span></a>
        </div>
    </form>


    <?php
    // Show error message if set
    if (isset($_GET['error'])) {
        echo "<p style='color:red'>" . htmlspecialchars($_GET['error']) . "</p>";
    }
    ?>
</body>

</html>