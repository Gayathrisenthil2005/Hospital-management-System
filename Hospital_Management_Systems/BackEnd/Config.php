<?php
$host = "localhost";  // Server name (usually localhost for WAMP)
$user = "root";       // Default username for WAMP
$password = "";       // Default password for WAMP (empty)
$database = "hospital";  // Your database name

// Create connection
$mysqli = new mysqli($host, $user, $password, $database);

// Check connection
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}
?>
