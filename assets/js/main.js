window.addEventListener("DOMContentLoaded", () => {

    const toggleChat = document.getElementById("chat-icon-toggle");
    const closeChat = document.getElementById("close-chat-btn");
    const formBlock = document.querySelector(".form-wrapper");


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

});



