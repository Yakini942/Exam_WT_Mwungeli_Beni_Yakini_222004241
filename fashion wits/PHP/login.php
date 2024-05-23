<?php
session_start();
require 'config.php';

// Initialize variables
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs
    $username = htmlspecialchars(stripslashes(trim($_POST['username'])));
    $password = htmlspecialchars(stripslashes(trim($_POST['password'])));
    $login_type = htmlspecialchars(stripslashes(trim($_POST['login_type'])));

    // Determine which table and ID column to query based on the login type
    switch ($login_type) {
        case 'user':
            $table = 'users';
            $id_column = 'user_id';
            $redirect = '../dashboard.php';
            break;
        case 'stylist':
            $table = 'stylists';
            $id_column = 'stylist_id';
            $redirect = '../stylist_dashboard.php';
            break;
        case 'admin':
            $table = 'admins';
            $id_column = 'admin_id';
            $redirect = '../admin_dashboard.html';
            break;
        default:
            $error = "Invalid login type.";
            $table = '';
            $id_column = '';
            $redirect = '';
    }

    if ($table && $id_column && $redirect) {
        // Fetch user data
        $sql = "SELECT $id_column, password FROM $table WHERE username = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $error = "Database error: " . $conn->error;
        } else {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($user_id, $stored_password);
                $stmt->fetch();

                // Debug: Output fetched data for verification
                error_log("Fetched data: ID = $user_id, Stored Password = $stored_password");

                // Check if the input password matches the stored password
                if ($password === $stored_password) {
                    // Password is correct, start a session
                    $_SESSION[$id_column] = $user_id;
                    $_SESSION['username'] = $username;
                    header("Location: $redirect");
                    exit();
                } else {
                    $error = "Invalid username or password.";
                }
            } else {
                $error = "Invalid username or password.";
            }

            $stmt->close();
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login users</title>
    <link rel="stylesheet" href="../CSS/dash.css">
</head>
<body>
    <div class="container">
        <header>
            <nav>
                <a href="../index.html">FASHION DESIGN</a>
                <a href="../index.html" class="active">Home</a>
                <a href="../about.html">About</a>
                <a href="#services">Services</a>
                <a href="#contact">Contact</a>
            </nav>
        </header>
        <nav class="sidemenu">
            <ul>
                <li><a href="#" onclick="showSection('user_login')">Users</a></li>
                <li><a href="#" onclick="showSection('stylist_login')">Stylist</a></li>
                <li><a href="#" onclick="showSection('admin_login')">Admin</a></li>
            </ul>
        </nav>
        <main>
            <section id="user_login">
                <div class="content">
                    <h2>User Login</h2>
                    <?php if ($error && $login_type === 'user'): ?>
                        <p class="error"><?php echo htmlspecialchars($error); ?></p>
                    <?php endif; ?>
                    <form method="post" action="login.php">
                        <input type="hidden" name="login_type" value="user">
                        <label for="username_user">Username</label>
                        <input type="text" id="username_user" name="username" required>

                        <label for="password_user">Password</label>
                        <input type="password" id="password_user" name="password" required>

                        <button type="submit">Login</button>
                        <p>Don't Have An Account!</p>
                        <div class="button"><a href="../account.html">Sign Up</a></div>
                    </form>
                </div>
            </section>
            <section id="stylist_login" class="hidden">
                <div class="content">
                    <h2>Stylist Login</h2>
                    <?php if ($error && $login_type === 'stylist'): ?>
                        <p class="error"><?php echo htmlspecialchars($error); ?></p>
                    <?php endif; ?>
                    <form method="post" action="login.php">
                        <input type="hidden" name="login_type" value="stylist">
                        <label for="username_stylist">Username</label>
                        <input type="text" id="username_stylist" name="username" required>

                        <label for="password_stylist">Password</label>
                        <input type="password" id="password_stylist" name="password" required>

                        <button type="submit">Login</button>
                    </form>
                </div>
            </section>
            <section id="admin_login" class="hidden">
                <div class="content">
                    <h2>Admin Login</h2>
                    <?php if ($error && $login_type === 'admin'): ?>
                        <p class="error"><?php echo htmlspecialchars($error); ?></p>
                    <?php endif; ?>
                    <form method="post" action="login.php">
                        <input type="hidden" name="login_type" value="admin">
                        <label for="username_admin">Username</label>
                        <input type="text" id="username_admin" name="username" required>

                        <label for="password_admin">Password</label>
                        <input type="password" id="password_admin" name="password" required>

                        <button type="submit">Login</button>
                        <p>ONLY FOR ADMINS</p>
                    </form>
                </div>
            </section>
        </main>
    </div>

    <script src="../scrip/user_dashboard.js"></script>
</body>
</html>
