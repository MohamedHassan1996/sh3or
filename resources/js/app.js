import './bootstrap'; // Make sure this imports correctly
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
//     auth: {
//         headers: {
//             //user: 'codewithgun',
//             //password: 'gunwithcode',
//             'Authorization': 'Bearer ' + localStorage.getItem('token'),
//         }
//     }
// });

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1',
//     //wsHost: 'shueur-app.shueur.com',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
//     encrypted: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     auth: {
//         headers: {
//             'Authorization': 'Bearer ' + localStorage.getItem('token'),
//         }
//     }
// });


window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '865dc1ca593866642712',
    cluster: 'mt1',
    wsHost: 'apptest.masar-soft.com',
    wsPort: 443,
    wssPort: 443,
    forceTLS: true,
    enabledTransports: ['ws', 'wss'],
    encrypted: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
    auth: {
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token'),
        }
    }
});


//const chatId = 1; // Set this dynamically in your Blade file if needed

// window.Echo.private(`chat.${chatId}`)
//     .subscribed(() => {
//         console.log(`Successfully subscribed to chat.${chatId}`);
//     })
//     .error((error) => {
//         console.error(`Failed to subscribe to chat.${chatId}:`, error);
//     })
//     .listen('.home', (data) => {
//         console.log('Message received:', data);
//         // Handle the incoming message here
//     });


/*window.Echo.private(`chat.1`)
    .listen('.home', (e) => {
        console.log(e.message );
    });*/
