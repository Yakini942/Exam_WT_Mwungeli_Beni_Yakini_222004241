<?php
require 'config.php'; // Database connection
session_start();

// Fetch recommendations data
$sql = "SELECT * FROM recommendations";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data as JSON
    $recommendations = array();
    while ($row = $result->fetch_assoc()) {
        $recommendations[] = $row;
    }
    echo json_encode($recommendations);
} else {
    echo json_encode(array()); // No recommendations found
}

$conn->close();
?>
