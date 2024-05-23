<?php
require 'config.php';

// Initialize variables
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs
    $username = htmlspecialchars(stripslashes(trim($_POST['username'])));
    $expertise = htmlspecialchars(stripslashes(trim($_POST['expertise'])));
    $email = htmlspecialchars(stripslashes(trim($_POST['email'])));
    $action = htmlspecialchars(stripslashes(trim($_POST['action'])));

    // Process availability input
    $availability = $_POST['availability'];
    $availability_json = json_encode($availability);

    if ($action == 'add') {
        // Add stylist to the database
        $sql = "INSERT INTO stylists (username, expertise, email, availability) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssss", $username, $expertise, $email, $availability_json);
            if ($stmt->execute()) {
                $success = "Stylist added successfully.";
            } else {
                $error = "Error adding stylist: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Database error: " . $conn->error;
        }
    } elseif ($action == 'remove') {
        // Remove stylist from the database
        $sql = "DELETE FROM stylists WHERE username = ? AND email = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ss", $username, $email);
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $success = "Stylist removed successfully.";
                } else {
                    $error = "Stylist not found.";
                }
            } else {
                $error = "Error removing stylist: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Database error: " . $conn->error;
        }
    } else {
        $error = "Invalid action.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stylist Management</title>
    <link rel="stylesheet" href="../CSS/dash.css">
</head>
<body>
    <div class="container">
        <h2>Manage Stylists</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php elseif ($success): ?>
            <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <a href="../admin_dashboard.html" class="btn">Go Back</a>
    </div>
</body>
</html>
