<?php
// Database connection settings
$servername = "localhost";  // Server where MySQL is running
$username = "root";         // MySQL username (change if needed)
$password = "";             // MySQL password (change if needed)
$dbname = "library";        // Database name from your SQL file

// Create connection using mysqli_connect()
// Parameters: server, username, password, database name
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check if connection failed
if (!$conn) {
    // mysqli_connect_error() returns the connection error message
    die("Connection failed: " . mysqli_connect_error());
}
?>