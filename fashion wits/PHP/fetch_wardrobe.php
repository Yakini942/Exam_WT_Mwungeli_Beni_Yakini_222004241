<?php
require 'config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch wardrobe items for the user
$sql = "SELECT * FROM wardrobe WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$wardrobe_items = array();
while ($row = $result->fetch_assoc()) {
    $wardrobe_items[] = $row;
}

echo json_encode($wardrobe_items);

$stmt->close();
$conn->close();
?>
