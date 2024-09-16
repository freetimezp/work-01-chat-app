window.addEventListener("DOMContentLoaded", () => {

    //toggle chat
    const toggleChat = document.getElementById("chat-icon-toggle");
    const closeChat = document.getElementById("close-chat-btn");
    const formBlock = document.querySelector(".form-block");

    toggleChat.addEventListener("click", () => {
        if (formBlock.classList.contains("active")) {
            formBlock.classList.remove("active");
        } else {
            formBlock.classList.add("active");
        }
    })

    closeChat.addEventListener("click", () => {
        formBlock.classList.remove("active");
    })


    //send form data
    document.getElementById("chatForm").addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent form submission

        const name = document.getElementById("username").value || "Quest";
        const email = document.getElementById("useremail").value || "no email";
        const topic = document.getElementById("topic").value;
        const message = document.getElementById("message").value;
        const token = generateToken(); // Token generation function

        const data = {
            name: name,
            email: email,
            topic: topic,
            message: message,
            token: token
        };

        // AJAX Request to submit the form
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "submit_message.php", true);
        xhr.setRequestHeader("Content-type", "application/json");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText); // Response from server
                //alert("Message sent successfully!");

                document.getElementById("username").value = '';
                document.getElementById("useremail").value = '';
                document.getElementById("topic").value = '';
                document.getElementById("message").value = '';
            }
        };

        xhr.send(JSON.stringify(data));
    });

    // Generate a simple token for user identification
    function generateToken() {
        return Math.random().toString(36).substring(7); // Basic token generation for demonstration
    }

});



