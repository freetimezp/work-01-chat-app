<?php
session_start(); // starts the session
//save main page in session
$_SESSION['url'] = $_SERVER['REQUEST_URI'];

require("connect.php");
require("functions.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Project | Chat App</title>

    <script>
        let currentMessageCount = 0; // Store the current count of messages

        // Poll for message count every 5 seconds
        setInterval(function() {
            checkMessageCount();
        }, 5000); // Poll every 5 seconds

        // Function to check the count of messages from the server
        function checkMessageCount() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "get_message_count.php", true); // Server endpoint to get the message count
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    const newMessageCount = parseInt(xhr.responseText);
                    if (newMessageCount !== currentMessageCount) { // Only fetch messages if the count changes
                        currentMessageCount = newMessageCount;
                        fetchNewMessages(); // Fetch new messages
                    }
                }
            };
            xhr.send();
        }

        // Function to fetch new messages from the server
        function fetchNewMessages() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "get_new_messages.php", true); // Server endpoint to get new messages
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("messageList").innerHTML = xhr.responseText; // Update the messages list
                }
            };
            xhr.send();
        }
    </script>
</head>

<body>
    <header class="header">
        <span>
            Лого
        </span>

        <?php if (!isset($_SESSION['user_email'])): ?>
            <form class="form-login" method="POST" action="login.php">
                <input type="text" placeholder="email" name="email" required>
                <input type="password" placeholder="password" name="password" required>
                <button type="submit">Логин</button>
            </form>
        <?php else: ?>
            <form class="manager-user" method="POST" action="logout.php">
                <span>Hello Manager - <b><?php echo $_SESSION['user_email']; ?></b></span>
                <button type="submit" class="logout">logout</button>
            </form>
        <?php endif ?>
    </header>
    <?php show($_SESSION); ?>

    <div class="container">
        <div class="form-block" id="form-block">
            <div class="form-wrapper">
                <h2>Розпочніть бесіду</h2>
                <hr>

                <div class="form-content" id="messageList"></div>
                <hr>

                <form id="chatForm">
                    <input type="hidden" id="hidden_user_id" name="hidden_user_id"
                        value="<?= $_SESSION['user_id']; ?>" />
                    <input type="hidden" id="token" name="token" value="">

                    <label for="topic" id="select-label">Тема (обери зі списку):</label>
                    <select id="topic" name="topic" required>
                        <option value="Topic 1" selected>Тема 1</option>
                        <option value="Topic 2">Тема 2</option>
                        <option value="Topic 3">Тема 3</option>
                    </select>

                    <textarea id="message" name="message" rows="4" cols="50" placeholder="Наберіть текст повідомлення"
                        required></textarea>

                    <div class="btn-block">
                        <button type="submit" <?= !isset($_SESSION['user_email']) && 'disabled'; ?>>Відправити</button>
                    </div>
                </form>

                <div class="form-close-btn" id="close-chat-btn">
                    <i class="ri-close-large-line"></i>
                </div>
            </div>
        </div>

        <div class="chat-icon" id="chat-icon-toggle">
            <box-icon name='chat'></box-icon>
        </div>
    </div>





    <footer class="footer">
        footer
    </footer>



    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script src="./assets/js/main.js"></script>
</body>

</html>