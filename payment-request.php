<?php

// Start the session
session_start();
require 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = isset($_POST['amount']) ? $_POST['amount'] * 100 : null;
    $purchase_order_id = isset($_POST['purchase_id']) ? $_POST['purchase_id'] : null;
    $purchase_order_name = isset($_POST['product_name']) ? $_POST['product_name'] : null;
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $phone = isset($_POST['phone']) ? $_POST['phone'] : null;

    if (!$amount || !$purchase_order_id || !$purchase_order_name || !$name || !$email || !$phone) {
        die("Missing required fields. Please ensure all fields are filled.");
    }

    $postField = array(
        "return_url" => "http://localhost/E-COMMERCE/payment-request.php",
        "website_url" => "http://localhost/E-COMMERCE/",
        "amount" => $amount,
        "purchase_order_id" => $purchase_order_id,
        "purchase_order_name" => $purchase_order_name,
        "customer_info" => array(
            "name" => $name,
            "email" => $email,
            "phone" => $phone,
        ),
    );

    $jsonData = json_encode($postField);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/initiate/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_HTTPHEADER => array(
            'Authorization: key live_secret_key_68791341fdd94846a146f0457ff7b455', // Replace with your actual key
            'Content-Type: application/json',
        ),
    ));

    $response = curl_exec($curl);
    if (curl_errno($curl)) {
        echo 'Error: ' . curl_error($curl);
    } else {
        $responseArray = json_decode($response, true);
        if (isset($responseArray['error'])) {
            echo 'Error: ' . $responseArray['error'];
        } elseif (isset($responseArray['payment_url'])) {
            header('Location: ' . $responseArray['payment_url']);
        } else {
            echo 'Unexpected response';
            print_r($response);
        }
    }

    curl_close($curl);
} else {
    die("Invalid request method. Please submit the form.");
}
