<?php
session_start();
require 'PHP/config.php';

// Check if the stylist is logged in
if (!isset($_SESSION['stylist_id'])) {
    header('Location: PHP/login.php');
    exit();
}

$stylist_id = $_SESSION['stylist_id'];

// Fetch appointments for the logged-in stylist
$sql = "SELECT appointment_id, user_id, appointment_time, status FROM appointments WHERE stylist_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $stylist_id);
$stmt->execute();
$result = $stmt->get_result();
$appointments = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = htmlspecialchars(stripslashes(trim($_POST['current_password'])));
    $new_password = htmlspecialchars(stripslashes(trim($_POST['new_password'])));
    $confirm_password = htmlspecialchars(stripslashes(trim($_POST['confirm_password'])));

    if ($new_password !== $confirm_password) {
        $password_error_message = "New passwords do not match.";
    } else {
        // Verify current password
        $sql = "SELECT password FROM stylists WHERE stylist_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $stylist_id);
        $stmt->execute();
        $stmt->bind_result($stored_password);
        $stmt->fetch();
        $stmt->close();

        if ($current_password === $stored_password) {
            // Update password
            $sql = "UPDATE stylists SET password = ? WHERE stylist_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_password, $stylist_id);

            if ($stmt->execute()) {
                $password_success_message = "Password changed successfully.";
            } else {
                $password_error_message = "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $password_error_message = "Current password is incorrect.";
        }
    }
}


// Handle product addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = htmlspecialchars(stripslashes(trim($_POST['name'])));
    $category = htmlspecialchars(stripslashes(trim($_POST['category'])));
    $price = htmlspecialchars(stripslashes(trim($_POST['price'])));
    $brand = htmlspecialchars(stripslashes(trim($_POST['brand'])));
    $description = htmlspecialchars(stripslashes(trim($_POST['description'])));
    $image_url = htmlspecialchars(stripslashes(trim($_POST['image_url'])));

    $sql = "INSERT INTO products (name, category, price, brand, description, image_url) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsis", $name, $category, $price, $brand, $description, $image_url);

    if ($stmt->execute()) {
        $success_message = "Product added successfully.";
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stylist Dashboard</title>
    <link rel="stylesheet" href="CSS/dash.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Welcome, Stylist</h1>
        </header>
        <nav class="sidemenu">
            <ul>
                <li><a href="#" onclick="showSection('add_product')">Add Product</a></li>
                <li><a href="#" onclick="showSection('view_appointments')">View Appointments</a></li>
                <li><a href="#" onclick="showSection('change_password')">Change Password</a></li>
                <li><a href="PHP/logout.php">Logout</a></li>
            </ul>
        </nav>
        <main>
            <section id="add_product">
              <div class="content">
                <h2>Add Product</h2>
                <?php if (isset($success_message)): ?>
                    <p class="success"><?php echo htmlspecialchars($success_message); ?></p>
                <?php elseif (isset($error_message)): ?>
                    <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
                <?php endif; ?>
                <form method="post" action="stylist_dashboard.php">
                    <input type="hidden" name="add_product" value="1">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" required>

                    <label for="category">Category</label>
                    <input type="text" id="category" name="category" required>

                    <label for="price">Price</label>
                    <input type="number" step="0.01" id="price" name="price" required>

                    <label for="brand">Brand</label>
                    <input type="text" id="brand" name="brand" required>

                    <label for="description">Description</label>
                    <textarea id="description" name="description"></textarea>

                    <label for="image_url">Image URL</label>
                    <input type="url" id="image_url" name="image_url">

                    <button type="submit">Add Product</button>
                </form>
              </div>
            </section>
            <section id="view_appointments" class="hidden">
                <h2>View Appointments</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Appointment ID</th>
                            <th>User ID</th>
                            <th>Appointment Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appointment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($appointment['appointment_id']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['user_id']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
            <section id="change_password" class="hidden">
              <div class="content">
                <h2>Change Password</h2>
                <?php if (isset($password_success_message)): ?>
                    <p class="success"><?php echo htmlspecialchars($password_success_message); ?></p>
                <?php elseif (isset($password_error_message)): ?>
                    <p class="error"><?php echo htmlspecialchars($password_error_message); ?></p>
                <?php endif; ?>
                <form method="post" action="stylist_dashboard.php">
                    <input type="hidden" name="change_password" value="1">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required>

                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>

                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>

                    <button type="submit">Change Password</button>
                </form>
              </div>
            </section>
        </main>
    </div>
    <script>
        function showSection(sectionId) {
            const sections = document.querySelectorAll('main section');
            sections.forEach(section => {
                section.classList.add('hidden');
            });
            document.getElementById(sectionId).classList.remove('hidden');
        }
    </script>
</body>
</html>
