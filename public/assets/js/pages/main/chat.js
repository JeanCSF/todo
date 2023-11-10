function startComet() {
    setTimeout(function () {
        fetch("chat/get_messages")
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erro na requisição: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Lógica para exibir as mensagens recebidas
                // Implemente aqui a lógica para exibir as mensagens recebidas no front-end
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
    newChat()
    // startComet();
    document.getElementById("frmMessage").addEventListener("submit", (e) => {
        e.preventDefault();
        var message = document.getElementById("message").value;
        sendMessage(message);
        document.getElementById("message").value = "";

    });
});

async function sendMessage(chatId, message) {
    try {
        const response = await fetch(`${BASEURL}/messages/send_message`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                chatId: chatId,
                message: message
            }),
        });

        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.status}`);
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error("Erro na requisição:", error);
        throw error;
    }
}

async function newChat() {
    try {
        const response = await fetch(`${BASEURL}/messages/new_chat`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                session_user_id: session_user_id,
                chat_user_name: chat_user_name
            }),
        });

        if (!response.ok) {
            throw new Error(`Erro na requisição: ${response.status}`);
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error("Erro na requisição:", error);
        throw error;
    }
}

