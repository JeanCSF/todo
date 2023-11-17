const messagesContainer = document.querySelector("#messagesContainer");
var lastProcessedMessageId = null;

function processMessages(messages) {
    messages.forEach(message => {
        const messageDiv = createElement('div', {
            class: message.user_id == session_user_id ? 'message my-message' : 'message'
        });
        messageDiv.textContent = message.message;
        messagesContainer.appendChild(messageDiv);
    });
}

function startComet() {
    setTimeout(function () {
        fetch(`${BASEURL}/messages/get_last_message/${php_chat_id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erro na requisição: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (lastProcessedMessageId !== data[0].message_id) {
                    lastProcessedMessageId = data[0].message_id
                    processMessages(data);
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            })
            .catch(error => {
                console.error("Erro na requisição:", error);
            })
            .finally(() => {
                startComet();
            });
    }, 1000);
}

document.addEventListener("DOMContentLoaded", function () {
    if (php_chat_id) {
        startComet();
        loadMessages()
    }
    if (chat_user_name) {
        document.getElementById("frmMessage").addEventListener("submit", (e) => {
            e.preventDefault();
            var message = document.getElementById("message").value;
            newMessage(message);
            document.getElementById("message").value = "";

        });
    }
});

async function newMessage(message) {
    try {
        const response = await fetch(`${BASEURL}/messages/send_message`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                chat_user_name: chat_user_name,
                message: message
            }),
        });
        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.status}`);
        }

        // const myMessage = createElement('div', {
        //     class: 'message my-message'
        // });
        // myMessage.textContent = message;

        // messagesContainer.appendChild(myMessage);
        // const data = await response.json();
        // return data;

    } catch (error) {
        console.error("Erro na requisição:", error);
        throw error;
    }
}
async function loadMessages() {
    try {
        const response = await fetch(`${BASEURL}/messages/get_messages/${php_chat_id}`, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            },
        });
        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.status}`);
        }

        const Messages = await response.json();
        const lastMessageIndex = Messages.length - 1;
        Messages.splice(lastMessageIndex, 1);
        Messages.forEach(message => {
            const MessageDiv = createElement('div', {
                class: message.user_id == session_user_id ? 'message my-message' : 'message'
            });
            MessageDiv.textContent = message.message;
            messagesContainer.appendChild(MessageDiv);
        });
    } catch (error) {
        console.error("Erro na requisição:", error);
        throw error;
    }
}

