# Tbcconnect Example Small Consent Application 

## About This Application

We create simple small **laravel** application to demonstrate how we have used these five request in that project and how we are giving customer data to your tracker.

# Install & run application
```
// 1. run composer
composer install

// 2. create .env file
cp .env.example .env

// 3. modify this credentials in .env file
CLIENT_ID=
CLIENT_SECRET=

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

// 4. generate APP_KEY
php artisan key:generate

// 5. run migration
php artisan migrate

// 6. run localhost
php artisan serve

```
