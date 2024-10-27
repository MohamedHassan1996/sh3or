<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Test with Echo</title>
    @vite('resources/js/app.js')

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f9f9f9;
        }
        h1 {
            margin-top: 20px;
        }
        #chat-container {
            width: 400px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-top: 20px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        #messages {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            max-height: 300px;
            background-color: #fff;
        }
        .message {
            margin: 5px 0;
            padding: 10px;
            border-radius: 15px;
            position: relative;
        }
        .sender {
            background-color: #d1e7dd;
            align-self: flex-end; /* Align sender messages to the right */
        }
        .receiver {
            background-color: #f8d7da;
            align-self: flex-start; /* Align receiver messages to the left */
        }
        #input-container {
            display: flex;
            padding: 10px;
            border-top: 1px solid #ccc;
        }
        #message-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }
        #send-button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #send-button:hover {
            background-color: #0056b3;
        }
        #chat-counter {
            font-size: 14px;
            margin-bottom: 10px;
        }
        .user-status {
            margin: 5px;
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
    <h1>Chat Test with Echo</h1>
    <div id="chat-counter">0</div> <!-- Chat counter to track messages -->
    <div id="chat-users">
        <div id="user-status-3" class="user-status">User 1: Offline</div>
        <div id="user-status-4" class="user-status">User 2: Offline</div>
        <!-- Add more user statuses as needed -->
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
            console.log("Document is loaded.");

            const chatCounterElement = document.getElementById('chat-counter');
            let chatId = 2; // Replace this with the actual conversation ID
            const senderId = localStorage.getItem('userId'); // Replace with the actual senderId
            //const reciverId = localStorage.getItem('reciverId');


        });

        // Function to update user status in the UI
        function updateUserStatus(userId, status) {
            const userStatusElement = document.getElementById(`user-status-${userId}`);
            if (userStatusElement) {
                userStatusElement.textContent = status === 'online' ? 'Online' : 'Offline';
                userStatusElement.className = status === 'online' ? 'user-status status online' : 'user-status status offline';
            }
        }

        // Function to send a message
        function sendMessage() {
            const messageInput = document.getElementById('message-input');
            const message = messageInput.value;
            const chatId = 2; // Replace this with the actual conversation ID
            const senderId = localStorage.getItem('userId'); // Replace with the actual senderId
            const partyData = {
                "customerId": 5,
                "partyId": 2,
                "date": "2024-10-05",
                "preparationTimeId": 1,
                "cityId" : 1
            }
            // Retrieve the token from localStorage
            const token = localStorage.getItem('token'); // Replace 'token' with the actual key you use

            fetch('/api/customer-api/reservations/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token,
                },
                body:JSON.stringify(partyData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                //chatId = data.chatId;
                console.log('Message sent:', data);
                messageInput.value = ''; // Clear input field
                if (window.Echo) {
                console.log("Echo is defined.");


                    var channel = Echo.join(`chat.${chatId}`);
                    channel.here((members) => {
                        // For example
                        console.log('Members:', members); // Attempt to see raw output

                        members.forEach((member) => {
                            if(senderId != member.id){
                                updateUserStatus(member.id, 'online');
                            }
                        });
                    });

                    channel.joining((member) => {
                        // For example
                        console.log('Member joined:', member); // Attempt to see raw output
                        if(senderId != member.id){
                            updateUserStatus(member.id, 'online');
                        }
                    });

                    channel.leaving((member) => {
                        // For example
                        if(senderId != member.id){
                            updateUserStatus(member.id, 'online');
                        }
                    });


                // Listen for new messages
                Echo.private(`chat.${chatId}`)
                    .listen('.home', (e) => {
                        console.log("Event received.");
                        console.log('Message received:', e);

                        // Display the received message
                        const messageContainer = document.getElementById('messages');

                        if (e.senderId != senderId) {
                            const newMessage = document.createElement('div');
                            newMessage.className = 'message receiver'; // Class for received messages
                            newMessage.textContent = `${e.senderName}: ${e.message} (${e.createdAt})`;
                            messageContainer.appendChild(newMessage);
                            messageContainer.scrollTop = messageContainer.scrollHeight; // Scroll to the bottom
                        }

                        const newMessage = document.createElement('div');
                            newMessage.className = 'message sender'; // Class for received messages
                            newMessage.textContent = `You: ${e.message}`;
                            messageContainer.appendChild(newMessage);
                            messageContainer.scrollTop = messageContainer.scrollHeight; // Scroll to the bottom


                        // Update chat counter
                        chatCounterElement.textContent = parseInt(chatCounterElement.textContent) + 1;
                    })
                    .error((error) => {
                        console.error("Subscription error:", error);
                    });

            } else {
                console.error('Echo is not defined.');
            }
            })
            .catch(error => {
                console.error('Error sending message:', error);
            });
        }
    </script>
</body>
</html>
