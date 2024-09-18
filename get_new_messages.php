<?php
require_once 'connect.php';

// Fetch all messages from the database
$query = "SELECT * FROM messages ORDER BY created_at ASC";
$result = $conn->query($query);

if ($result->num_rows) {
    while ($row = $result->fetch_assoc()) {
        echo '
            <div class="message-block">
                <div class="message-block__header">
                    <div class="message-avatar">
                        ' . 'A' .  ' 
                    </div>
                    <div class="message-name">
                        ' . htmlspecialchars($row['user_id']) .  ' 
                    </div>
                    <div class="message-date">
                        ' . $row['created_at'] .  ' 
                    </div>
                    <div class="message-topic">
                        ' . htmlspecialchars($row['topic']) .  ' 
                    </div>
                </div>
                <div class="message-block__content">
                    <p>
                        ' . htmlspecialchars($row['message_text']) . '
                    </p>
                </div>
            </div>
        ';
    }
} else {
    echo "No messages found.";
}
