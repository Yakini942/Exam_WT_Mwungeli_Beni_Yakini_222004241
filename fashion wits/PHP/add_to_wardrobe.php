<?php
require 'config.php'; // Database connection
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $custom_description = $_POST['custom_description'];
    $acquired_at = $_POST['acquired_at'];

    $stmt = $conn->prepare("INSERT INTO wardrobe (user_id, product_id, custom_description, acquired_at) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $user_id, $product_id, $custom_description, $acquired_at);

    if ($stmt->execute()) {
        echo "Item added to wardrobe successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
