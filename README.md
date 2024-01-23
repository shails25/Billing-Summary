## Billing Summary Application

### steps to run this app

- clone this repo on your local
- copy .env.example to .env
- change ``DB_DATABASE, DB_USERNAME, DB_PASSWORD``
- run ``composer install``
- run ``php artisan migrate``
- run ``php artisan db:seed``
- run ``php artisan serve``
- open ``127.0.0.8000`` in your browser
