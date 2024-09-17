window.addEventListener("DOMContentLoaded", () => {

    //toggle chat
    const toggleChat = document.getElementById("chat-icon-toggle");
    const closeChat = document.getElementById("close-chat-btn");
    const formBlock = document.querySelector(".form-block");
    const formContent = document.getElementById("messageList");

    if (toggleChat) {
        toggleChat.addEventListener("click", () => {
            if (formBlock.classList.contains("active")) {
                if (formContent.classList.contains("active")) {
                    formContent.classList.remove("active");
                    formContent.classList.add("check");
                }
                formBlock.classList.remove("active");
            } else {
                formContent.classList.remove("check");
                formBlock.classList.add("active");
            }
        })
    }

    if (closeChat) {
        closeChat.addEventListener("click", () => {
            formBlock.classList.remove("active");
            if (formContent.classList.contains("active")) {
                formContent.classList.remove("active");
            }
        });
    }



    //send form data
    document.getElementById("chatForm").addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent form submission

        const user_id = document.getElementById("hidden_user_id").value || "";
        //console.log(user_id);

        const topic = document.getElementById("topic").value;
        const message_text = document.getElementById("message").value;
        const token = generateToken(); // Token generation function
        //console.log(token);

        const data = {
            user_id: user_id,
            topic: topic,
            message_text: message_text,
            token: token
        };

        //console.log(data);

        // AJAX Request to submit the form
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "submit_message.php", true);
        xhr.setRequestHeader("Content-type", "application/json");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText); // Response from server
                //alert("Message sent successfully!");

                //clear form
                document.getElementById("topic").value = '';
                document.getElementById("message").value = '';
            }
        };

        xhr.send(JSON.stringify(data));
    });

    // Generate a simple token for user identification
    function generateToken() {
        return Math.random().toString(36); // Basic token generation for demonstration
    }

});



