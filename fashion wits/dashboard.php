<?php
session_start();
require 'PHP/config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: PHP/login.php'); // Redirect to login page if not logged in
    exit();
}

// Retrieve user information
$user_id = $_SESSION['user_id'];
$sql = "SELECT username FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="CSS/dash.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Welcome, <?php echo htmlspecialchars($username); ?></h1>
            <button id="logoutButton">Logout</button>
        </header>
        <nav class="sidemenu">
            <ul>
                <li><a href="#" onclick="showSection('profile')">Profile</a></li>
                <li><a href="#" onclick="showSection('schedule_appointment')">Schedule Appointment</a></li>
                <li><a href="#" onclick="showSection('add_to_wardrobe')">Add to Wardrobe</a></li>
                <!-- Place this where you want the logout button to appear -->


            </ul>
        </nav>
        <main>
            <section id="recommendations">
                <h2>Recommendations</h2>
                <div class="sliding-cards" id="recommendationsContent">
                    <!-- Recommendation cards will be dynamically loaded here -->
                </div>
            </section>
            <section id="wardrobe">
                <h2>Your Wardrobe</h2>
                <div id="wardrobeContent">
                    <!-- Wardrobe items will be dynamically loaded here -->
                </div>
            </section>
            <section id="profile" class="hidden">
              <div class="content">
                <h2>Your Profile</h2>
                <!-- Profile details form -->
                <form action="PHP/profile.php" method="POST">
                  <label for="body_type">Body Type:</label><br>
                  <input type="text" id="body_type" name="body_type" required><br>

                  <label for="style_preferences">Style Preferences:</label><br>
                  <textarea id="style_preferences" name="style_preferences" rows="2" cols="30" required></textarea><br>

                  <label for="budget">Budget:</label><br>
                  <input type="number" id="budget" name="budget" required><br>

                  <label for="specific_needs">Specific Needs:</label><br>
                  <textarea id="specific_needs" name="specific_needs" rows="2" cols="30" required></textarea><br>

                  <button type="submit" value="Save Profile">Save Profile</button>
                  </form>
                </div>
            </section>
            <section id="schedule_appointment" class="hidden">
                <div class="content">
                <h2>Schedule Appointment</h2>
                <!-- Schedule appointment form -->

                <form id="scheduleAppointmentForm" method="post" action="PHP/schedule_appointment.php">
                    <label for="stylist_id">Select Stylist</label>
                    <select id="stylist_id" name="stylist_id" required>
                      <option value="">Select Stylist</option>
                      <?php
                          // Query the database to fetch stylist usernames
                            $sql = "SELECT stylist_id, username FROM stylists";
                              $result = $conn->query($sql);

                                // Check if there are results
                                if ($result && $result->num_rows > 0) {
                                  // Loop through the result set
                                while ($row = $result->fetch_assoc()) {
                                  // Output each stylist username as an option
                                  echo '<option value="' . $row['stylist_id'] . '">' . $row['username'] . '</option>';
                                }
                              }
                              ?>
                    </select>

                    <label for="appointment_time">Appointment Time:</label>
                <input type="datetime-local" id="appointment_time" name="appointment_time"><br><br>

                    <button type="submit">Schedule Appointment</button>
                </form>
                <div id="scheduleMessage"></div>
              </div>
            </section>
            <section id="add_to_wardrobe" class="hidden">
              <div class="content">
                <h2>Add to Wardrobe</h2>
                <!-- Add to wardrobe form -->
                <form id="wardrobeForm">
                  <label for="stylist_id">Select Stylist</label>
                  <select id="stylist_id" name="stylist_id" required>
                    <option value="">Select product</option>
                    <?php
                        // Query the database to fetch stylist usernames
                          $sql = "SELECT product_id, name FROM products";
                            $result = $conn->query($sql);

                              // Check if there are results
                              if ($result && $result->num_rows > 0) {
                                // Loop through the result set
                              while ($row = $result->fetch_assoc()) {
                                // Output each stylist username as an option
                                echo '<option value="' . $row['product_id'] . '">' . $row['name'] . '</option>';
                              }
                            }
                            ?>
                  </select>
                <label for="custom_description">Description:</label>
                <input type="text" id="custom_description" name="custom_description"><br><br>
                <label for="acquired_at">Acquired At:</label>
                <input type="date" id="acquired_at" name="acquired_at"><br><br>

                <button type="button" onclick="addToWardrobe()">Add to Wardrobe</button>
            </form>
            <div id="wardrobeList"></div>
          </div>
          
            </section>
        </main>
    </div>
    <script src="scrip/user_dashboard.js"></script>
</body>
</html>
