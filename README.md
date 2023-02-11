# Foodics Technical Task

### Task Installation steps:

```bash
git clone https://gitlab.com/norhanelnezamy/foodics-task.git
```
Move into your source code, and then run the following commands one by one
```bash
$ cp -i .env.example .env
$ php artisan key:generate
$ composer install
```

```Please set the database connection and email configs into your .env file.```

To seed data into database run:
```bash
$ php artisan db:seed
```

### Testing Configs Steps:
```bash
$ cp -i .env .env.testing
```

``` Change testing database name.```

To run unit test cases run:
```bash
$ php artisan test
```

To use the place order API:
```bash
POST http://127.0.0.1:8080/api/order
Content-Type: application/json
Body:
{
"products": [
        {
        "product_id": 1,
        "quantity": 2
        },
        {
        "product_id": 2,
        "quantity": 1
        }
    ]
}
```
