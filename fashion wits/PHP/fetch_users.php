<?php
require 'config.php'; // Database connection
session_start();

// Fetch users data
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data as JSON
    $users = array();
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users);
} else {
    echo json_encode(array()); // No users found
}

$conn->close();
?>
