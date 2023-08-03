
## Project Setup
1.  Clone the project.
2. Setup the DB using .env file in the project root structure.
`DB_CONNECTION=mysql`
`DB_HOST=127.0.0.1`
`DB_PORT=3306`
`DB_DATABASE=time_track`
`DB_USERNAME=root`
`DB_PASSWORD=`
3.  Go to the project directory and run below command.
`composer install`
`php artisan serve`
`php artisan migrate`
`php artisan db:seed`
4.  Browse lo `http://localhost:8000/login`
5.  For testing `php artisan test`
6. Credentials
    Email: `test@example.com`
    Password: `password`