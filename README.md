# TaskMan

### About
This project is my attempt at the Niyo Group task

## Installation


## Web sockets
By default, the application runs Laravel Reverb on port localhost:8080. These can be configured further in the .env file. The web socket can be used outside laravel as well.

Follow this process to set up web sockets
* Install Laravel Echo and Pusher
    ```bash
  npm install --save laravel-echo pusher-js
    ```

## Testing
This project uses phpunit for feature tests. Run the following command to run the tests
```bash
php artisan test
```


php artisan serve
php artisan queue:listen
php artisan reverb:start

// for frontend example
npm run dev
