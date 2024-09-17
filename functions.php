<?php



//show array keys and values
function show($arr)
{
    foreach ($arr as $key => $value) {
        echo "<pre>" . $key . ": " . $value . "</pre>";
    }
}
//get messages for current user
function getMessagesCurrentUser($id)
{
    if (!$id) return;

    $conn = require("connect.php");

    $query = "SELECT * FROM messages WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;  // Append each message to the array
    }

    // Optionally, print all messages for debugging
    //print_r($messages);

    return $messages;  // Return the array of messages
}
