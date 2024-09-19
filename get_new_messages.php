<?php
require_once 'connect.php';
session_start();

// Check if the role and user ID are set in the session
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Modify the query depending on the user's role
if ($role === 'admin') {
    // Admin sees all messages
    $query = "SELECT messages.*, users.name, users.role 
              FROM messages 
              JOIN users ON messages.user_id = users.user_id
              ORDER BY messages.created_at ASC";
} elseif ($role === 'manager') {
    // Manager sees messages only for their assigned topic
    $query = "SELECT messages.*, users.name, users.role 
              FROM messages 
              JOIN users ON messages.user_id = users.user_id
              JOIN topics ON messages.topic = topics.title
              WHERE topics.manager_id = ?
              ORDER BY messages.created_at ASC";
} else {
    // Regular users see only their messages and any responses to their messages
    $query = "SELECT messages.*, users.name, users.role 
              FROM messages 
              JOIN users ON messages.user_id = users.user_id
              WHERE messages.user_id = ? 
              OR messages.answer_to IN (
                  SELECT token FROM messages WHERE user_id = ?
              )
              ORDER BY messages.created_at ASC";
}

$stmt = $conn->prepare($query);

if ($role === 'manager') {
    // Bind manager's user ID to filter messages by assigned topic
    $stmt->bind_param("s", $userId);
} elseif ($role !== 'admin') {
    // Bind user ID for regular users (used twice for both user_id and answer_to logic)
    $stmt->bind_param("ss", $userId, $userId);
}

$stmt->execute();
$result = $stmt->get_result();

$messages = [];

while ($row = $result->fetch_assoc()) {
    $messages[] = $row;  // Collect all message rows
}

// Check if there are messages and display them
if (count($messages) > 0) {
    // Loop through the messages and display them
    foreach ($messages as $message) {
        // Determine additional class based on the sender's role
        $additionalClass = '';
        if ($message['role'] === 'admin') {
            $additionalClass = ' message-admin';
        } elseif ($message['role'] === 'manager') {
            $additionalClass = ' message-manager';
        }

        echo '
            <div class="message-block' . $additionalClass . '" data-token="' . $message['token']
            . '" onclick="replyToMessage(\'' . $message['token'] . '\')">
                <div class="message-block__header">
                    <div class="message-avatar">
                        ' . substr(htmlspecialchars($message['name']), 0, 1) . ' 
                    </div>
                    <div class="message-name">
                        ' . htmlspecialchars($message['name']) . '  
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
