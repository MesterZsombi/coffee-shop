# Coffee Shop – Laravel Web Application

A Coffee Shop web application built with Laravel 13 as part of the Advanced Web Development coursework.

## Features

- Product and category management using Eloquent ORM
- REST API with Laravel Sanctum authentication
- Admin-only authorization for product management
- Blade and Tailwind CSS storefront
- Session-based shopping cart
- Checkout and order persistence using database transactions
- Custom 404 error page and application logging
- Product image uploads using Laravel Storage
- Queued order confirmation emails
- Automated feature and security tests using Pest
- API documentation using Scramble

## Technology Stack

- PHP 8.4
- Laravel 13
- SQLite
- Laravel Sanctum
- Blade & Tailwind CSS 4
- Vite
- Pest PHP
- Laravel Queue and Mail

## Local Installation

Clone the repository:

```bash
git clone https://github.com/MesterZsombi/coffee-shop.git
cd coffee-shop
```

Install dependencies:

```bash
composer install
npm ci
```

Set up the environment:

```bash
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
```

Configure `.env` for local development:

```dotenv
DB_CONNECTION=sqlite
QUEUE_CONNECTION=database
MAIL_MAILER=log
```

Run migrations and seed sample products:

```bash
php artisan migrate
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=ProductSeeder
php artisan storage:link
```

Start the application:

```bash
php artisan serve
```

In another terminal:

```bash
npm run dev
```

The application is available at `http://127.0.0.1:8000` by default.

## REST API

Public endpoints:

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/products` | List products |
| GET | `/api/products/{id}` | View a product |

Admin-only endpoints:

| Method | Endpoint | Description |
|---|---|---|
| POST | `/api/products` | Create a product |
| PUT/PATCH | `/api/products/{id}` | Update a product |
| DELETE | `/api/products/{id}` | Delete a product |
| POST | `/api/products/{id}/image` | Upload a product image |

Admin endpoints require Sanctum authentication and the `manage-products` permission.

API documentation is available at `/docs/api`.

## Shopping Cart and Checkout

Visitors can add products to their cart, change quantities, remove items, and submit an order.

The checkout process validates customer information, saves orders and order items in an SQLite database, and clears the cart after successful submission.

## Background Processing

Order confirmation emails are queued using Laravel's database queue driver.

Start a queue worker:

```bash
php artisan queue:work database --tries=3
```

For local development, `MAIL_MAILER=log` records email messages in application logs instead of sending them to real addresses.

## Testing

Tests use an isolated in-memory SQLite database.

Run all tests:

```bash
php artisan test
```

**Latest verified result:** 24 passing tests, 75 assertions.

Tests cover the REST API, authentication and authorization, image uploads, checkout, order confirmation emails, error handling, and security.

## Production Build

Build optimized frontend assets:

```bash
npm run build
```

Cache Laravel configuration, routes, and views:

```bash
php artisan optimize
```

Before deploying, configure production environment variables, secure database access, use HTTPS, disable debug mode, and configure persistent storage and a queue worker.

The project has been tested locally. A public production deployment is not included.

## Author

Mester Zsombor

Advanced Web Development – Laravel Coursework

## Repository

https://github.com/MesterZsombi/coffee-shop