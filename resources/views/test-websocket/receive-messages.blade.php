<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    @vite('resources/js/app.js') <!-- Load Vite compiled assets -->
</head>
<body>
    <div id="messages">
        test
        <!-- Messages will appear here -->
    </div>
    <div id="chat-counter">
        0
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        console.log("Document is loaded.");

        var chatCounterElement = document.getElementById('chat-counter');
        const conversationId = 1; // Replace this with the actual conversation ID

        if (window.Echo) {
            console.log("Echo is defined.");
            console.log("Subscribing to chat channel...");

            Echo.private(`chat.${conversationId}`)
                .listen('.home', (e) => {
                    console.log("Event received.");
                    console.log('Message received:', e);

                    // Display the received message
                    var messageContainer = document.getElementById('messages');
                    var newMessage = document.createElement('p');
                    newMessage.textContent = `${e.sender}: ${e.message} (${e.timestamp})`; // Adjust to include sender and timestamp
                    messageContainer.appendChild(newMessage);

                    // Update chat counter
                    chatCounterElement.textContent = parseInt(chatCounterElement.textContent) + 1;

                                // Optionally, emit read status when chat is opened
                    Echo.private(`chat.1`)
                    .whisper('.messages-marked-as-read', { chatId: 1, userId: 3 });

                    console.log("Whisper event sent successfully");

                    })
                .error((error) => {
                    console.error("Subscription error:", error);
                });


        } else {
            console.error('Echo is not defined.');
        }
    });

    </script>
</body>
</html>
