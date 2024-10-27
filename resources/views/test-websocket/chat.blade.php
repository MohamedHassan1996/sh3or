<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Interface</title>
    @vite('resources/js/app.js')

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f0f2f5;

        }
        h1 {
            margin-top: 20px;
            color: #333;
        }
        #chat-container {
            width: 100%;
            max-width: 600px;
            border-radius: 10px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.2);
            background-color: white;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }

        #messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            max-height: 500px;
            background-color: #fafafa;
        }
        #messages::-webkit-scrollbar {
    width: 6px; /* Set scrollbar width */
}

#messages::-webkit-scrollbar-track {
    background: #f1f1f1; /* Set the background color of the scrollbar track */
    border-radius: 50%
}

#messages::-webkit-scrollbar-thumb {
    background: #888; /* Set the color of the scrollbar thumb */
    border-radius: 20px

}

#messages::-webkit-scrollbar-thumb:hover {
    background: #555; /* Set the hover color of the scrollbar thumb */
}
        .message {
            margin: 10px 0;
            display: flex;
            align-items: flex-start;
            padding: 10px;
            border-radius: 10px;
        }
        .sender {
            justify-content: flex-end;
        }
        .receiver {
            justify-content: flex-start;
        }
        .message-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }
        .message-content {
            max-width: 80%;
            background-color: #e0f7fa;
            padding: 10px;
            border-radius: 15px;
        }
        .message.sender .message-content {
            background-color: #d1e7dd;
        }
        .message .message-info {
            font-size: 12px;
            color: #666;
        }
        #input-container {
            display: flex;
            padding: 15px;
            border-top: 1px solid #ccc;
            background-color: #fff;
        }
        #message-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }
        #send-button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        #send-button:hover {
            background-color: #0056b3;
        }
        #chat-counter {
            font-size: 14px;
            margin: 15px;
            color: #666;
        }
        .user-status {
            font-size: 14px;
            margin-left: 15px;
        }
        .status.online {
            color: green;
        }
        .status.offline {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Chat Interface</h1>
    <div id="chat-counter">Messages: 0</div>
    <div class="user-status">
        Status: <span id="user-status" class="status offline">offline</span>
    </div>
    <div id="chat-container">
        <div id="messages"></div>
        <div id="input-container">
            <input type="text" id="message-input" placeholder="Enter your message" />
            <button id="send-button" onclick="sendMessage()">Send</button>
        </div>
    </div>

    <script>
document.addEventListener("DOMContentLoaded", () => {
    const chatCounterElement = document.getElementById('chat-counter');
    const messageContainer = document.getElementById('messages');
    const chatId = {{ $chatId }};
    const senderId = localStorage.getItem('userId');
    const token = localStorage.getItem('token');

    // Function to render a message in the chat
    function renderMessage(message) {
        const isSender = message.senderId == senderId;
        const newMessage = document.createElement('div');
        newMessage.classList.add('message', isSender ? 'sender' : 'receiver');

        // Only display avatar and name if the message is not from the current user
        let avatarHtml = '';
        let nameHtml = '';
        if (!isSender) {
            const avatarUrl = message.senderAvatar ? message.senderAvatar : 'default-avatar.png';
            avatarHtml = `<img src="${avatarUrl}" class="message-avatar" />`;
            nameHtml = `<div class="message-info">${message.senderName} - ${message.sendDate} ${message.sendTime}</div>`;
        } else {
            nameHtml = `<div class="message-info">${message.sendDate} ${message.sendTime}</div>`; // Only show date and time for your own messages
        }

        newMessage.innerHTML = `
            ${avatarHtml}
            <div class="message-content">
                <div style="text-align: ${isSender ? 'right' : 'left'};">${message.message}</div>
                ${nameHtml}
            </div>`;

        messageContainer.appendChild(newMessage);
        messageContainer.scrollTop = messageContainer.scrollHeight;

        // Logic to mark messages as unread
        if (!isSender) {
            markMessageAsRead(message.messageId); // Call to mark the message as read
        }
    }

    // Function to mark messages as read
    function markMessageAsRead(messageId) {
        fetch('/api/chats/unread-message/update', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({ chatId, senderId, messageId })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Message marked as read:', data);
        })
        .catch(error => {
            console.error('Error marking message as read:', error);
        });
    }

    // Fetch existing chat messages on page load
    function loadMessages() {
        fetch(`/api/chats/messages?chatId=${chatId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
        })
        .then(response => response.json())
        .then(data => {
            const messages = data.data.chatMesssages;
            messages.forEach(renderMessage);
            chatCounterElement.textContent = `Messages: ${messages.length}`;
        })
        .catch(error => {
            console.error('Error fetching messages:', error);
        });
    }

    // Function to send a new message
    function sendMessage() {
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value;

        if (message.trim() === '') return; // Avoid sending empty messages

        fetch('/api/chats/messages/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({ message, chatId, senderId })
        })
        .then(response => response.json())
        .then(() => {
            messageInput.value = ''; // Clear input field
        })
        .catch(error => {
            console.error('Error sending message:', error);
        });
    }

    // Listen for new messages via WebSocket
    function listenForNewMessages() {
        if (window.Echo) {
            Echo.private(`chat.${chatId}`)
                .listen('.home', (e) => {
                    const newMessage = {
                        senderId: e.senderId,
                        senderName: e.senderName,
                        senderAvatar: e.senderAvatar,
                        message: e.message,
                        sendDate: e.sendDate,
                        sendTime: e.sendTime,
                        messageId: e.messageId // Ensure you have a messageId here
                    };

                    renderMessage(newMessage); // Render the new message
                    chatCounterElement.textContent = `Messages: ${parseInt(chatCounterElement.textContent.split(': ')[1]) + 1}`;
                });

            // Check the online status of other users
            Echo.join(`chat.${chatId}`)
                .here((members) => {
                    const userStatusElement = document.getElementById('user-status');
                    const isOnline = members.some(member => member.id != senderId);
                    userStatusElement.textContent = isOnline ? 'online' : 'offline';
                });
        }
    }

    // Event listener for the send button
    document.getElementById('send-button').addEventListener('click', sendMessage);

    // Load messages and set up WebSocket listener
    loadMessages();
    listenForNewMessages();
});



    </script>
</body>
</html>
