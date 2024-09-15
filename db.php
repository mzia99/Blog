<?php
$host = 'localhost';  // Change this as per your setup
$user = 'root';       // Your MySQL username
$pass = 'ziablade'; // Your MySQL password
$dbname = 'blog';     // Database name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

