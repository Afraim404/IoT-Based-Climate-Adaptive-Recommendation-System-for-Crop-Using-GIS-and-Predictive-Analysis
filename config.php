<?php
// Database configuration
$host = "localhost";     // MySQL server (localhost since it's local)
$user = "root";          // Default MySQL user for XAMPP/WAMP
$password = "";          // Default password is empty in XAMPP
$dbname = "esp_data";    // Database name (you’ll create this)

// API Key (must match ESP8266 PROJECT_API_KEY)
$PROJECT_API_KEY = "1919";

// Create database connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
