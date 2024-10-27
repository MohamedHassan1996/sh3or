<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Chats</title>
    @vite('resources/js/app.js') <!-- Load Vite compiled assets -->
</head>
<body>
    <div class="container">
        <h1>All Chats</h1>
        <div id="chat-list">
            <!-- Chat boxes will be dynamically inserted here -->
        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', () => {
    const chatListElement = document.getElementById('chat-list');
    const unreadCounts = {}; // Store unread counts for each chat
    const userId = localStorage.getItem('userId'); // Get userId from local storage

    // Function to fetch all chats based on userId
    async function fetchChats() {
        const token = localStorage.getItem('token'); // Get token from local storage

        if (!userId || !token) {
            console.error('User ID or token not found in local storage');
            return;
        }

        try {
            const response = await fetch(`/api/chats?userId=${userId}`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`, // Set the Authorization header
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`Failed to fetch chats: ${response.statusText}`);
            }

            const { data } = await response.json();
            displayChats(data.chats);
        } catch (error) {
            console.error(error);
        }
    }

    // Function to display chats in the chat list
    function displayChats(chats) {
        // Clear existing chat list
        chatListElement.innerHTML = '';

        // Create chat boxes for each chat
        chats.forEach(chat => {
            const chatBox = createChatBox(chat);
            chatListElement.appendChild(chatBox);
        });

        // Listen for new messages on the allChats channel
        window.Echo.channel(`allChats.${userId}`)
            .listen('.all-chats', handleNewMessage);
    }

    // Function to create a chat box element
    function createChatBox(chat) {
        const chatBox = document.createElement('div');
        chatBox.classList.add('chat-box');
        chatBox.setAttribute('data-chat-id', chat.chatId);
        unreadCounts[chat.chatId] = chat.unreadCount ?? 0; // Initialize unread count

        // Determine if the message is from the user
        const messagePrefix = chat.lastMessageFromMe ? 'You: ' : '';

        chatBox.innerHTML = `
            <img src="${chat.avatar || 'default-image.jpg'}" alt="${chat.name}" class="chat-image">
            <div class="chat-details">
                <h3>${chat.name}</h3>
                <p class="last-message">${messagePrefix}${chat.message || 'No messages yet'}</p>
                <span class="unread-count" ${chat.unreadCount === 0 ? 'style="display:none;"' : ''}>Unread: ${unreadCounts[chat.chatId]}</span>
            </div>
        `;

        // Add click event listener to redirect to the chat route
        chatBox.addEventListener('click', () => {
            window.location.href = `chat/${chat.chatId}`; // Redirect to chat page with chatId
        });

        return chatBox;
    }

    // Function to handle new incoming messages
    function handleNewMessage(data) {
        console.log('New message:', data);

        const chatBox = chatListElement.querySelector(`[data-chat-id='${data.chatId}']`);
        if (chatBox) {
            const lastMessageElement = chatBox.querySelector('.last-message');
            const messagePrefix = data.lastMessageFromMe ? 'You: ' : '';
            lastMessageElement.textContent = messagePrefix + data.message;

            // Increment the unread message count
            unreadCounts[data.chatId] = (unreadCounts[data.chatId] || 0) + 1;
            const unreadCountElement = chatBox.querySelector('.unread-count');
            unreadCountElement.textContent = 'Unread: ' + unreadCounts[data.chatId];
            unreadCountElement.style.display = 'inline'; // Show unread count
        }
    }

    // Initial fetch of chats
    fetchChats();
});
    </script>

<style>
/* Main container styling */
.container {
    max-width: 900px;
    margin: 40px auto;
    padding: 20px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    border-radius: 15px;
    font-family: 'Roboto', sans-serif;
}

/* Title styling */
h1 {
    text-align: center;
    font-size: 2.5rem;
    color: #343a40;
    margin-bottom: 30px;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.1);
}

/* Chat box styling */
.chat-box {
    display: flex;
    align-items: center;
    padding: 15px;
    margin-bottom: 15px;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    border: 1px solid #e0e0e0;
}

.chat-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    border-color: #007bff; /* Accent color on hover */
}

/* Chat image styling */
.chat-image {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    margin-right: 20px;
    object-fit: cover;
    border: 2px solid #ced4da; /* Soft border */
    transition: border-color 0.3s ease;
}

.chat-box:hover .chat-image {
    border-color: #007bff;
}

/* Chat details styling */
.chat-details {
    flex-grow: 1;
    position: relative;
}

.chat-details h3 {
    font-size: 1.6rem;
    font-weight: 600;
    color: #495057;
    margin: 0;
}

.chat-details p {
    font-size: 1rem;
    margin: 5px 0 0;
    color: #868e96;
}

/* Unread count badge */
.unread-count {
    font-size: 0.85rem;
    font-weight: bold;
    padding: 5px 12px;
    background-color: #ff6b6b;
    color: #fff;
    border-radius: 50px;
    position: absolute;
    top: 10px;
    right: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease;
}

.chat-box:hover .unread-count {
    background-color: #ff4757; /* Darker red on hover */
}

/* Scrollbar for chat list */
#chat-list {
    max-height: 600px;
    overflow-y: auto;
    padding-right: 5px;
}

#chat-list::-webkit-scrollbar {
    width: 8px;
}

#chat-list::-webkit-scrollbar-thumb {
    background-color: #ced4da;
    border-radius: 10px;
}

#chat-list::-webkit-scrollbar-thumb:hover {
    background-color: #495057;
}

/* Responsive design for mobile devices */
@media screen and (max-width: 768px) {
    .chat-box {
        flex-direction: column;
        align-items: flex-start;
        padding: 10px;
    }

    .chat-image {
        width: 50px;
        height: 50px;
        margin-bottom: 10px;
    }

    .chat-details h3 {
        font-size: 1.4rem;
    }

    .chat-details p {
        font-size: 0.9rem;
    }

    .unread-count {
        position: static;
        margin-top: 5px;
    }
}
</style>
</body>
</html>
