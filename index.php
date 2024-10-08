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
        let activeMessageToken = null; // Variable to store the token of the active message

        // Poll for message count every 5 seconds
        setInterval(function() {
            checkMessageCount();

            reapplyActiveMessage();
        }, 5000); // Poll every 5 seconds
        setInterval(function() {
            $.ajax({
                url: 'get_new_messages.php',
                method: 'GET',
                success: function(response) {
                    // Update message list
                    $('#messageList').html(response);

                    // Reapply active message after the update
                    reapplyActiveMessage();
                }
            });
        }, 5000);


        // Function to check the count of messages from the server
        function checkMessageCount() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "get_message_count.php", true); // Server endpoint to get the message count
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    const newMessageCount = parseInt(xhr.responseText);
                    if (newMessageCount !== currentMessageCount) { // Only fetch messages if the count changes
                        currentMessageCount = newMessageCount;

                        check = document.getElementById("messageList").classList.contains("check");

                        if (!check) {
                            document.getElementById("messageList").classList.add("active");
                        }

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

                    // After updating the message list, reapply the 'message-active' class
                    reapplyActiveMessage();

                    scrollToBottom(); // Optional: Scroll to the bottom
                }
            };
            xhr.send();
        }

        // Function to scroll to the bottom of the message list
        function scrollToBottom() {
            var messageList = document.getElementById('messageList');
            messageList.scrollTop = messageList.scrollHeight; // Scroll to the bottom
        }



        function replyToMessage(token) {
            // Remove 'message-active' from all messages
            document.querySelectorAll('.message-block').forEach(function(messageBlock) {
                messageBlock.classList.remove('message-active');
            });

            // Add 'message-active' class to the clicked message
            let clickedMessage = document.querySelector(`[data-token='${token}']`);
            if (clickedMessage) {
                clickedMessage.classList.add('message-active');
            }

            // Store the selected message's token
            activeMessageToken = token;

            // Optionally store in localStorage to persist across page reloads
            localStorage.setItem('activeMessageToken', token);

            document.getElementById('answerTo').value = token;
        }

        // Function to reapply 'message-active' class after refresh
        function reapplyActiveMessage() {
            const storedToken = localStorage.getItem('activeMessageToken');
            if (storedToken) {
                let activeMessage = document.querySelector(`[data-token='${storedToken}']`);
                if (activeMessage) {
                    activeMessage.classList.add('message-active');
                }
            }
        }

        function clearAnswerToken() {
            activeMessageToken = null;
            document.getElementById('answerTo').value = "";
            localStorage.removeItem('activeMessageToken');
            document.querySelectorAll('.message-block').forEach(function(messageBlock) {
                messageBlock.classList.remove('message-active');
            });
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
                <span>Hello - <b><?php echo $_SESSION['user_email']; ?></b></span>
                <button type="submit" class="logout">logout</button>
            </form>
        <?php endif ?>
    </header>

    <?php
    show($_SESSION);
    ?>

    <div class="container">
        <div class="form-block" id="form-block">
            <div class="form-wrapper">
                <div class="form-content" id="messageList"></div>

                <form id="chatForm">
                    <input type="hidden" id="hidden_user_id" name="hidden_user_id"
                        value="<?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>" />
                    <input type="hidden" id="token" name="token" value="">

                    <input type="hidden" id="answerTo" name="answer_to" value="" />

                    <label for="topic" id="select-label">Тема (обери зі списку):</label>
                    <select id="topic" name="topic" required>
                        <option value="Lorem ipsume text 1" selected>Lorem ipsume text 1</option>
                        <option value="Lorem ipsume text 2">Lorem ipsume text 2</option>
                        <option value="Lorem ipsume text 3">Lorem ipsume text 3</option>
                    </select>

                    <?php if (
                        isset($_SESSION['user_id'])
                        && ($_SESSION['role'] === "manager" || $_SESSION['role'] === "admin")
                    ): ?>
                        <div class="clear-answer-btn" onclick="clearAnswerToken()">
                            Clear Answer Token
                        </div>
                    <?php endif; ?>


                    <textarea id="message" name="message" rows="4" cols="50" placeholder="Наберіть текст повідомлення"
                        required></textarea>

                    <div class="btn-block">
                        <div class="btn form-close-btn" id="close-chat-btn">
                            Закрити
                        </div>
                        <button class="btn" type="submit" <?= !isset($_SESSION['user_email']) && 'disabled'; ?>>Відправити</button>
                    </div>
                </form>


            </div>
        </div>

        <div class="chat-icon" id="chat-icon-toggle">
            <box-icon name='chat'></box-icon>
        </div>
    </div>





    <footer class="footer">
        footer
    </footer>


    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script src="./assets/js/main.js"></script>
</body>

</html>