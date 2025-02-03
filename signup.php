<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $address = $_POST['address'];

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $address);

    if ($stmt->execute()) {
        $_SESSION['user'] = $email;
        header("Location: index.php?message=" . urlencode("Signup successful!"));
    } else {
        header("Location: index.php?message=" . urlencode("Error: " . $stmt->error));
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Link to your CSS file -->
</head>

<body>
    <!-- Signup Form -->
    <div id="signup-form">
        <h2>Signup</h2>
        <form action="signup.php" method="POST">
            <label for="signup-name">Name:</label>
            <input type="text" id="signup-name" name="name" required>

            <label for="signup-email">Email:</label>
            <input type="email" id="signup-email" name="email" required>

            <label for="signup-password">Password:</label>
            <input type="password" id="signup-password" name="password" required>

            <label for="signup-address">Address:</label>
            <textarea id="signup-address" name="address" required></textarea>

            <button type="submit">Signup</button>
        </form>
    </div>
</body>

</html>