<?php
require 'config.php'; // Database connection
session_start();

// Fetch stylists data
$sql = "SELECT * FROM stylists";
$result = $conn->query($sql);

if ($result) {
    // Check if any rows were returned
    if ($result->num_rows > 0) {
        // Output data as JSON
        $stylists = array();
        while ($row = $result->fetch_assoc()) {
            $stylists[] = $row;
        }
        echo json_encode($stylists);
    } else {
        echo json_encode(array()); // No stylists found
    }
} else {
    // Error occurred during query execution
    echo json_encode(array("error" => "Query execution failed: " . $conn->error));
}

$conn->close();
?>
