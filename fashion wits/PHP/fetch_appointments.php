<?php
require 'config.php'; // Database connection
session_start();

// Fetch appointments data
$sql = "SELECT * FROM appointments";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data as JSON
    $appointments = array();
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
    echo json_encode($appointments);
} else {
    echo json_encode(array()); // No appointments found
}

$conn->close();
?>
