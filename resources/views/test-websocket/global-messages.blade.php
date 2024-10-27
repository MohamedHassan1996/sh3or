{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    @vite('resources/js/app.js') <!-- Load Vite compiled assets -->
</head>
<body>
    <div>
        message counter
    </div>
    <div>
        <a id="chat-counter" href="http://127.0.0.1:8000/api/all-chats">0</a>
    </div>

    <script>
        // Ensure this code runs after the Echo instance is initialized
        document.addEventListener("DOMContentLoaded", () => {
            console.log("Document is loaded.");

            var chatCounterElement = document.getElementById('chat-counter');

            if (window.Echo) {
                console.log("Echo is defined.");
                console.log("Subscribing to home-channel...");
                Echo.channel('public-channel')
                    .listen('.home', (e) => {
                        console.log("Event received.");
                        console.log('Message received:', e.message);

                        chatCounterElement.textContent = parseInt(chatCounterElement.textContent) + 1;
                    });
            } else {
                console.error('Echo is not defined.');
            }
        });
    </script>
</body>
</html> --}}


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    @vite('resources/js/app.js') <!-- Load Vite compiled assets -->
</head>
<body>
    <div>
        message counter
    </div>
    <div>
        <a id="chat-counter" href="http://127.0.0.1:8000/api/all-chats">0</a>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            console.log("Document is loaded.");

            var chatCounterElement = document.getElementById('chat-counter');

            // Get saved token and userId from local storage
            const token = localStorage.getItem('token'); // Assuming token is saved in local storage as 'token'
            const userId = localStorage.getItem('userId'); // Assuming userId is saved in local storage as 'userId'

            if (token && userId) {
                console.log("Token and userId found in local storage.");

                // Fetch unread message count from API when the page loads
                fetch(`http://127.0.0.1:8000/api/chats/unread-message?userId=${userId}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`, // Send token in Authorization header
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.unreadCount !== undefined) {
                        chatCounterElement.textContent = data.unreadCount; // Update counter with unread messages
                    }
                })
                .catch(error => console.error('Error fetching unread messages:', error));
            } else {
                console.error('Token or userId not found in local storage.');
            }

            // Listen for new chat messages in real time using Echo
            if (window.Echo) {
                console.log("Echo is defined.");
                console.log("Subscribing to home-channel...");
                Echo.channel(`chatNotification.${userId}`)
                    .listen('.chat-notification', (e) => {
                        console.log("Event received.");
                        console.log('Message received:', e.message);

                        // Update the counter when a new message is received
                        chatCounterElement.textContent = parseInt(chatCounterElement.textContent) + 1;
                    });
            } else {
                console.error('Echo is not defined.');
            }
        });
    </script>
</body>
</html>
