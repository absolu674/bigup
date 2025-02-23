
## About Big up

Big Up is a dedication app. Make by Samba Tech


## Deployment command

- composer install
- php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
- php artisan jwt:secret


## Generate test data

- php artisan db:seed
- php artisan db:seed TestDataSeeder
- php artisan db:seed AdminSeeder
    - admin account info
        - email: admin@example.com
        - password: password