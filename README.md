# Product Search API

A Laravel REST API for searching and filtering products efficiently using MySQL full-text search, price and category filters, stock availability, sorting, and pagination.

## Features

- **Full-Text Search:** Search product names and descriptions.
- **Price Filtering:** Filter products by minimum and maximum price.
- **Category Filtering:** Retrieve products from a specific category.
- **Stock Filtering:** Return only products currently in stock.
- **Sorting:** Sort by price or creation date in ascending or descending order.
- **Pagination:** Paginated results with a maximum of 50 products per page.
- **Input Validation:** Validate query parameters and reject invalid filters.
- **Automated Tests:** Feature and unit tests covering search, filtering, sorting, validation, and pagination.

## Tech Stack

- PHP 8.3+
- Laravel 13
- MySQL / MariaDB
- PHPUnit / Laravel Testing

## Requirements

Make sure the following are installed:

- PHP 8.3 or later
- Composer
- MySQL or MariaDB
- Git

## Installation

### 1. Clone the repository

```bash
git clone <YOUR_REPOSITORY_URL>
cd product-search-api
```

Replace `<YOUR_REPOSITORY_URL>` with the actual Git repository URL.

### 2. Install dependencies

```bash
composer install
```

### 3. Configure the environment

Copy the example environment file:

```bash
copy .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

### 4. Configure the database

Create a database and update the following values in your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=product_search
DB_USERNAME=root
DB_PASSWORD=
```

Use the credentials configured on your local machine.

### 5. Run migrations

```bash
php artisan migrate
```

If the project includes a database seeder, follow its implementation or the project's setup instructions to populate sample data.

**Note:** Do not run `php artisan migrate:fresh` against a database containing data you need to preserve.

### 6. Start the development server

```bash
php artisan serve
```

The API will be available at:

```text
http://127.0.0.1:8000
```

## API Documentation

### Search Products

**Endpoint:** `GET /api/products/search`

Example:

```http
GET http://127.0.0.1:8000/api/products/search
```

### Query Parameters

| Parameter | Type | Description |
|---|---|---|
| `q` | string | Search product names and descriptions |
| `min_price` | numeric | Minimum product price |
| `max_price` | numeric | Maximum product price |
| `category_id` | integer | Filter by category ID |
| `in_stock` | boolean | Filter by stock availability |
| `sort_by` | string | Sort by `price` or `created_at` |
| `sort_order` | string | Sorting direction: `asc` or `desc` |
| `page` | integer | Page number, starting from 1 |
| `per_page` | integer | Number of results per page, from 1 to 50 |

### Example Requests

Search by keyword:

```http
GET /api/products/search?q=electronics
```

Filter by price range:

```http
GET /api/products/search?min_price=100&max_price=500
```

Filter by category:

```http
GET /api/products/search?category_id=1
```

Return only products in stock:

```http
GET /api/products/search?in_stock=true
```

Sort by price in ascending order:

```http
GET /api/products/search?sort_by=price&sort_order=asc
```

Combine multiple filters:

```http
GET /api/products/search?category_id=1&min_price=100&in_stock=true&per_page=10
```

### Example Response

```json
{
  "data": [
    {
      "id": 1,
      "category_id": 1,
      "name": "Example Product",
      "description": "Example product description",
      "price": "199.99",
      "stock_quantity": 25,
      "created_at": "2026-10-11T10:00:00.000000Z"
    }
  ],
  "links": {
    "first": "...",
    "last": "...",
    "prev": null,
    "next": "..."
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "per_page": 20,
    "to": 1,
    "total": 1
  }
}
```

The example values are illustrative. Actual results depend on the database contents and active filters.

### Validation

The API rejects invalid query parameters, including:

- A minimum price greater than the maximum price.
- A page number below 1.
- A `per_page` value greater than 50 or below 1.
- An unsupported sorting field or direction.
- A nonexistent category ID.
- An invalid stock filter value.

Validation failures return a `422 Unprocessable Entity` response.

## Running Tests

Run the complete test suite:

```bash
php artisan test
```

The tests cover:

- Full-text product search.
- Price, category, and stock filters.
- Ascending and descending sorting.
- Pagination and pagination metadata.
- Empty search terms and unmatched results.
- Invalid query parameters and validation errors.

## Project Structure

```text
app/
├── Http/
│   ├── Controllers/
│   └── Requests/
└── Services/

routes/
└── api.php

tests/
├── Feature/
└── Unit/
```

The structure above is representative; actual files may differ depending on the implementation.

## License

This project uses the Laravel framework. Refer to the repository's license and dependency licenses for applicable terms.
