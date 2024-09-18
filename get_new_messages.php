<?php
require_once 'connect.php';
session_start();

// Check if the role and user ID are set in the session
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Query to join messages with users table to get the name from users table
if ($role === 'admin') {
    // Admin sees all messages
    $query = "SELECT messages.*, users.name 
              FROM messages 
              JOIN users ON messages.user_id = users.user_id
              ORDER BY messages.created_at ASC";
} else {
    // Regular user sees only their messages
    $query = "SELECT messages.*, users.name 
              FROM messages 
              JOIN users ON messages.user_id = users.user_id
              WHERE messages.user_id = ? 
              ORDER BY messages.created_at ASC";
}

$stmt = $conn->prepare($query);

if ($role !== 'admin') {
    $stmt->bind_param("s", $userId);  // Bind the user ID for regular users
}

$stmt->execute();
$result = $stmt->get_result();

$messages = [];

while ($row = $result->fetch_assoc()) {
    $messages[] = $row;  // Collect all message rows
}

if (count($messages) > 0) {
    // Loop through the messages and display them
    foreach ($messages as $message) {
        echo '
            <div class="message-block">
                <div class="message-block__header">
                    <div class="message-avatar">
                        ' . substr(htmlspecialchars($message['name']), 0, 1) . ' 
                    </div>
                    <div class="message-name">
                        ' . htmlspecialchars($message['name']) . '  <!-- Display user name from users table -->
                    </div>
                    <div class="message-date">
                        ' . htmlspecialchars($message['created_at']) . ' 
                    </div>
                    <div class="message-topic">
                        ' . htmlspecialchars($message['topic']) . ' 
                    </div>
                </div>
                <div class="message-block__content">
                    <p>
                        ' . htmlspecialchars($message['message_text']) . '
                    </p>
                </div>
            </div>
        ';
    }
} else {
    echo "No messages found.";
}
