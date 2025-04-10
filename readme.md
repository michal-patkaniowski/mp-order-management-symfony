# ORDER MANAGEMENT SYSTEM API DOCUMENTATION

## OVERVIEW:
- Built with PHP 8.3 + Symfony 7.2
- Manages order lifecycle (creation to fulfillment)
- REST API endpoints for all operations

---

## CORE FEATURES:
- Order creation with multiple items
- Status tracking (new → paid → shipped → cancelled)
- Automatic total price calculation
- Comprehensive validation

---

## API ENDPOINTS:

### [1] CREATE ORDER
**Endpoint**: `POST /orders`

**Request format**:
```json
{
  "items": [
    {
      "productId": "123",
      "productName": "Sample Product",
      "price": 29.99,
      "quantity": 2
    }
  ]
}
```

**Success response (201 Created)**:
```json
{
  "id": "550e8400-e29b-41d4-a716-446655440000",
  "status": "new",
  "total": 5998,
  "createdAt": "2023-11-15T10:30:00Z"
}
```

---

### [2] GET ORDER DETAILS
**Endpoint**: `GET /orders/{id}`

**Success response (200 OK)**:
```json
{
  "id": "550e8400-e29b-41d4-a716-446655440000",
  "status": "paid",
  "items": [...],
  "total": 5998,
  "createdAt": "2023-11-15T10:30:00Z"
}
```

---

### [3] UPDATE STATUS
**Endpoint**: `PATCH /orders/{id}`

**Request format**:
```json
{
  "status": "shipped"
}
```

---

## INSTALLATION INSTRUCTIONS:

### Clone repository:
```bash
git clone https://github.com/your-repo/order-system.git
cd order-system
```

### Install dependencies:
```bash
composer install
```

### Set up database:
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### Run test suite:
```bash
php bin/phpunit
```

### Start development server:
```bash
symfony server:start
```

---

## TECHNICAL NOTES:
- Uses UUIDs for order identification
- DateTime for all timestamps
- Doctrine ORM for persistence
- Symfony Validator for input validation
- PSR-12 compliant code style
- amounts expressed in cents/smallest currency units