<?php
// db_connect.php

// CORS headers - crucial for allowing your frontend to communicate with this backend
header("Access-Control-Allow-Origin: *"); // Allow requests from any origin (for development)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Allowed HTTP methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allowed headers
header("Content-Type: application/json"); // Always return JSON

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Database credentials
$servername = "localhost"; // Your database host
$username = "your_db_username"; // Your database username
$password = "your_db_password"; // Your database password
$dbname = "university_sports"; // The database name you created

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Log the error for debugging, but don't expose sensitive info to the client
    error_log("Connection failed: " . $conn->connect_error);
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit(); // Terminate script execution
}
?>
