<?php
$host = 'localhost';  // Change this as per your setup
$user = 'root';       // Your MySQL username
$pass = 'ziablade'; // Your MySQL password
$dbname = 'blog';     // Database name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();

// Dummy user ID for demonstration; replace with actual authentication
$_SESSION['user_id'] = 1; // The ID of the logged-in user
$_SESSION['is_admin'] = true; // Whether the user is an admin

?>

