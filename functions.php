<?php



//show array keys and values
function show($arr)
{
    foreach ($arr as $key => $value) {
        echo "<pre>" . $key . ": " . $value . "</pre>";
    }
}
//get messages for current user
function getMessagesCurrentUser($userId, $role)
{
    if (!$userId) return;

    $conn = require("connect.php");

    // If the role is 'admin', fetch all messages; otherwise, fetch messages for the current user only
    if ($role === 'admin') {
        $query = "SELECT * FROM messages ORDER BY created_at ASC"; // Admin sees all messages
    } else {
        $query = "SELECT * FROM messages WHERE user_id = ? ORDER BY created_at ASC"; // Regular user sees only their messages
    }

    $stmt = $conn->prepare($query);

    if ($role !== 'admin') {
        $stmt->bind_param("s", $userId);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    return $messages;
}
