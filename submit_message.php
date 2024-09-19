<?php
// Include database connection
require_once 'connect.php';

session_start();

// Get the JSON data from the request
$data = json_decode(file_get_contents("php://input"), true);

$role = isset($_SESSION['role']) ? $_SESSION['role'] : "user";
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($data) {
    // Default values from the data
    $topic = $data['topic'];
    $message_text = $data['message_text'];
    $token = $data['token'];
    $answer_to = (isset($data['answer_to']) && $role !== "user") ? $data['answer_to'] : null; // New field for answer
    $created_at = date("Y-m-d H:i:s", null);

    // If the user is a manager, get the assigned topic from the 'topics' table
    if ($role === 'manager') {
        $query_topic = "SELECT title FROM topics WHERE manager_id = ?";
        $stmt_topic = $conn->prepare($query_topic);
        $stmt_topic->bind_param("s", $user_id);
        $stmt_topic->execute();
        $result_topic = $stmt_topic->get_result();
        $topic_data = $result_topic->fetch_assoc();
        $stmt_topic->close();

        // Set the manager's assigned topic as the default topic
        if ($topic_data) {
            $topic = $topic_data['title'];
        }
    }

    // Insert the message into the database
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
