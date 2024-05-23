<?php
require 'config.php'; // Database connection
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $body_type = $_POST['body_type'];
    $style_preferences = $_POST['style_preferences'];
    $budget = $_POST['budget'];
    $specific_needs = $_POST['specific_needs'];

    // Check if profile exists
    $stmt = $conn->prepare("SELECT id FROM profiles WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update existing profile
        $stmt = $conn->prepare("UPDATE profiles SET body_type = ?, style_preferences = ?, budget = ?, specific_needs = ? WHERE user_id = ?");
        $stmt->bind_param("ssdsi", $body_type, $style_preferences, $budget, $specific_needs, $user_id);
    } else {
        // Create new profile
        $stmt = $conn->prepare("INSERT INTO profiles (user_id, body_type, style_preferences, budget, specific_needs) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issds", $user_id, $body_type, $style_preferences, $budget, $specific_needs);
    }

    if ($stmt->execute()) {
        echo "Profile saved successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
