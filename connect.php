<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chat_app";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

print_r($conn);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
