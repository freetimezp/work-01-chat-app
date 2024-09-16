<?php
// Include database connection
require_once 'connect.php';

// Get the JSON data from the request
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $name = $data['name'];
    $email = $data['email'];
    $topic = $data['topic'];
    $message = $data['message'];
    $token = $data['token'];

    // Insert message into the database
    $query = "INSERT INTO messages (user_name, user_email, topic, message, token) 
              VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("sssss", $name, $email, $topic, $message, $token);
        $stmt->execute();
        $stmt->close();

        echo json_encode(["status" => "success", "message" => "Message sent!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to send message"]);
    }
}
