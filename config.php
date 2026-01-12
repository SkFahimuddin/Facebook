<?php
// Database configuration for TheFacebook 2004
// Place this file in your project root

// Start session for user login tracking
session_start();

// Database credentials
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');  // Default XAMPP username
define('DB_PASSWORD', '');      // Default XAMPP password (empty)
define('DB_NAME', 'thefacebook');

// Create connection
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, 3306);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to UTF-8
mysqli_set_charset($conn, "utf8");

// Helper function to clean input data
function clean_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    // Remove htmlspecialchars from here - only escape for SQL
    return mysqli_real_escape_string($conn, $data);
}

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Get current user info
function get_logged_in_user() {
    global $conn;
    if (is_logged_in()) {
        $user_id = $_SESSION['user_id'];
        $query = "SELECT * FROM users WHERE id = $user_id";
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($result);
    }
    return null;
}
?>