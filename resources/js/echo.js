import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
    auth: {
        headers: {
            Authorization: 'Bearer JWT_TOKEN_HERE'
        }
    }
});

setTimeout(() => {
    window.Echo.private('App.Models.User.1')
        .listen('TaskAdded', (e) => {
            console.log("TaskAdded event received")
            console.log(e)
        })
        .listen('TaskUpdated', (e) => {
            console.log("TaskUpdated event received")
            console.log(e)
        })
        .listen('TaskDeleted', (e) => {
            console.log("TaskUpdated event received")
            console.log(e)
        })
}, 2000);

