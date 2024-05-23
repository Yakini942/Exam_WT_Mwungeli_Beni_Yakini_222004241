<?php
require 'config.php'; // Database connection
session_start();

// Fetch products data
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data as JSON
    $products = array();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode($products);
} else {
    echo json_encode(array()); // No products found
}

$conn->close();
?>
