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
        const token = generateToken(40); // Token generation function
        const answer_to = document.getElementById("answerTo").value;
        //console.log(token);

        const data = {
            user_id: user_id,
            topic: topic,
            message_text: message_text,
            token: token,
            answer_to: answer_to
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
                document.getElementById("message").value = '';
            }
        };

        xhr.send(JSON.stringify(data));
    });

    // Generate a simple token for user identification
    function generateToken(tokenLength) {
        let result = '';
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_*@#$%^&';
        const charactersLength = characters.length;
        let counter = 0;
        while (counter < tokenLength) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
            counter += 1;
        }
        return result;
    }

});



