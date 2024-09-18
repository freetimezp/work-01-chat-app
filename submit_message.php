<?php
// Include database connection
require_once 'connect.php';

session_start();

// Get the JSON data from the request
$data = json_decode(file_get_contents("php://input"), true);

$role = isset($_SESSION['role']) ? $_SESSION['role'] : "user";

if ($data) {
    //print_r($data);
    $user_id = $data['user_id'];
    $topic = $data['topic'];
    $message_text = $data['message_text'];
    $token = $data['token'];
    $answer_to = (isset($data['answer_to']) && $role !== "user") ? $data['answer_to'] : null; // New field for set answer

    $created_at = date("Y-m-d H:i:s", null);



    // Insert message into the database
    $query = "INSERT INTO messages (user_id, topic, message_text, token, answer_to, created_at) 
              VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ssssss", $user_id, $topic, $message_text, $token, $answer_to, $created_at);
        $stmt->execute();
        $stmt->close();

        echo json_encode(["status" => "success", "message" => "Message sent!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to send message"]);
    }
}
