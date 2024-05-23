// Fetches product recommendations for a user.
<?php
require 'config.php'; // Database connection
session_start();

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT p.name, p.category, p.price, p.brand, p.description, p.image_url FROM recommendations r JOIN products p ON r.product_id = p.product_id WHERE r.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$recommendations = [];
while ($row = $result->fetch_assoc()) {
    $recommendations[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($recommendations);
?>
