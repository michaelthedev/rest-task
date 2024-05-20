# TaskMan

## About
This project is my attempt at the [Niyo Group task](https://docs.google.com/document/d/1hZYonhtUCVZGW_HADGyM8FWSMc57_2QmEp31TrulJj4)

## Features
1. User Authentication
    * Registration
    * Login
    * JWT API token
    * Refresh token
    * Logout
2. Task Management
    * Create a task
    * Update a task
    * Delete a task
    * Get all tasks
    * Get a task by unique id
3. Real-time updates using websockets

## Installation
1. Prerequisites
    * PHP 8.3+
    * Laravel 11
    * Composer
    * Node.js (for websocket setup)
    * MySQL
2. Clone this repository
    ```bash
    git clone https://github.com/michaelthedev/rest-task.git
   
   cd rest-task
    ```
3. Install dependencies
    ```bash
    composer install
   npm install
    ```
4. Set up environment file
    ```bash
    cp .env.example .env
    ```
   Replace the database credentials in the .env file with your own
5. Generate application key
    ```bash
    php artisan key:generate
    ```
6. Run the migrations
    ```bash
    php artisan migrate
    ```
7. Regenerate JWT secret key (for API)
    ```bash
    php artisan jwt:secret
    ```
8. Start the development server
    ```bash
    php artisan serve
    ```

## API endpoints
Check the [Postman documentation](https://documenter.getpostman.com/view/10657913/2sA3QmEaG2) for more details about the API endpoints

<!-- endpoint table -->
| Method | Endpoint         | Description                                          |
|--------|------------------|------------------------------------------------------|
| POST   | `/auth/login`    | Login a user                                         |
| POST   | `/auth/register` | Register a user                                      |
| POST   | `/auth/logout`   | Logout a user (with authorization)                   |
| POST   | `/auth/refresh`  | Refresh a user token (with authorization)            |
| GET    | `/auth/user`     | Get the authenticated user  (with authorization)     |
| POST   | `/tasks`         | Create a task (with authorization)                   |
| GET    | `/tasks`         | Get all tasks (with authorization)                   |
| GET    | `/tasks/{uid}`   | Get a task by unique id (`uid`) (with authorization) |
| PUT    | `/tasks/{uid}`   | Update a task                                        |
| DELETE | `/tasks/{uid}`   | Delete a task                                        |

## Websockets and Real-time Updates
By default, the application runs Laravel Reverb on `localhost:8080`. These can be configured further in the .env file. The web socket can be used outside laravel as well.

More documentation about broadcasting and events in Laravel is available [on Laravel's documentation](https://laravel.com/docs/11.x/broadcasting). You can also check the [Laravel Reverb documentation](https://laravel.com/docs/11.x/reverb)

<b>Make sure to run this to regenerate your Reverb keys (on your laravel project)</b>
```bash
php artisan reverb:install
```

Follow this process to set up web sockets (whether in Laravel or outside Laravel)

1. Install Laravel Echo and Pusher
    ```bash
      npm install --save laravel-echo pusher-js
    ```
2. Set up Laravel Echo
    ```javascript
   import Echo from 'laravel-echo';
   import Pusher from 'pusher-js';
   
   window.Pusher = Pusher;
   window.Echo = new Echo({
     broadcaster: 'reverb',
     key: {{REVERB_APP_KEY}},
     wsHost: {{REVERB_HOST}},
     wsPort: {{REVERB_PORT}},
     wssPort: {{REVERB_PORT}},
     forceTLS: {{https or http}},
     enabledTransports: ['ws', 'wss'],
     auth: {
         headers: {
             Authorization: 'Bearer JWT_TOKEN_HERE'
         }
     }
    });
3. Listen for events
    ```javascript
   const userId = 1; // replace with actual user id
   window.Echo.private('App.Models.User.'+userId)
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
    ```
4. Start laravel, queue and reverb
    ```bash
    php artisan serve
    php artisan queue:listen
    php artisan reverb:start
    ```
5. See `resources/js/echo.js` for a sample implementation. 
You can also run `npm run dev` for the websocket sample

## Testing
This project uses phpunit for feature tests. Run the following command to run the tests
```bash
php artisan test
```
#### Test screenshot
![resize](https://github.com/michaelthedev/rest-task/assets/39175160/88e438f9-35ee-46fa-bc66-cd47655eb65e)


## License
This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Author
Name: [Michael Arawole](https://github.com/michaelthedev).

Email: michael@logad.net
