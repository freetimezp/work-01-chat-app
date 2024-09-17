<?php
// Include database connection
require_once 'connect.php';

// Get the JSON data from the request
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    //print_r($data);

    $user_id = $data['user_id'];
    $topic = $data['topic'];
    $message_text = $data['message_text'];
    $token = $data['token'];
    $created_at = date("Y-m-d", null);
    //print_r($created_at);

    // Insert message into the database
    $query = "INSERT INTO messages (user_id, topic, message_text, token, created_at) 
              VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("sssss", $user_id, $topic, $message_text, $token, $created_at);
        $stmt->execute();
        $stmt->close();

        echo json_encode(["status" => "success", "message" => "Message sent!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to send message"]);
    }
}
