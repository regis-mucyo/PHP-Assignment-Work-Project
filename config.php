<?php
// Database configuration - change these if needed
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'recipe_manager';

// Connect to database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check if connection worked
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>