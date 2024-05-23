<?php
require 'config.php'; // Database connection
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];

    // Check if username already exists
    $check_stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // Username already exists, display error message
        $error_message = "Username already exists. Please choose a different username.";
    } else {
        // Username is unique, proceed with user registration
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO users (email, first_name, last_name, username, password, gender) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $email, $firstName, $lastName, $username, $password, $gender);

        // Execute the statement
        if ($stmt->execute()) {
            // Registration successful, redirect to dashboard
            header("Location: ../dashboard.php");
            exit();
        } else {
            // Registration failed, display error message
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }

    // Close the check statement
    $check_stmt->close();
}

// Close the connection
$conn->close();
?>
