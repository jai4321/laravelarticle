1. Provide Mysql credential in .env file 
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravelarticle
    DB_USERNAME=root
    DB_PASSWORD=

2. Run below commands

    php artisan install:api
    php aritsan config:publish cors
    php artisan migrate
    php artisan serve
