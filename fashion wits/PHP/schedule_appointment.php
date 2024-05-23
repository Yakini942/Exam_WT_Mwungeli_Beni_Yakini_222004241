<?php
require 'config.php'; // Database connection
session_start();

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $stylist_id = $_POST['stylist_id'];
    $appointment_time = $_POST['appointment_time'];

    $stmt = $conn->prepare("INSERT INTO appointments (user_id, stylist_id, appointment_time, status) VALUES (?, ?, ?, 'scheduled')");
    $stmt->bind_param("iis", $user_id, $stylist_id, $appointment_time);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Appointment scheduled successfully";
    } else {
        $response['success'] = false;
        $response['message'] = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method";
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
