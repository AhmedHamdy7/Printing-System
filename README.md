# Printing Shop Management System

A complete printing shop management system built with Laravel 12, featuring role-based access control, invoice management, product catalog, and comprehensive reporting.

**Company:** Qeematech
**Assigned To:** Mustafa Fahmy
**Prepared By:** Eng. Mohamed Abdelrahman

## Features

- **Authentication & Authorization**
  - Role-based access control (Admin, Employee)
  - Permission-based actions using Spatie Permission package

- **User Management** (Admin only)
  - Create, edit, delete users
  - Assign roles to users
  - View all system users

- **Product Management** (Admin only)
  - Add, edit, delete products
  - Set product prices
  - Enable/disable products
  - Soft delete support

- **Invoice System**
  - Create print orders with multiple products
  - Customer information management
  - Automatic invoice numbering
  - Apply discounts
  - Generate PDF invoices
  - Admin can view all invoices
  - Employees can view only their own invoices

- **Reports** (Admin only)
  - Daily income reports
  - Monthly income reports
  - Product sales statistics
  - Employee performance reports

## Tech Stack

- **Framework:** Laravel 12
- **Frontend:** Blade Templates
- **Database:** MySQL
- **Packages:**
  - `spatie/laravel-permission` - Roles & Permissions
  - `spatie/laravel-query-builder` - Advanced Query Building
  - `barryvdh/laravel-dompdf` - PDF Generation
  - `laravel/breeze` - Authentication

## Installation

### Prerequisites

- PHP >= 8.2
- Composer
- MySQL
- Node.js & NPM

### Steps

1. **Clone the repository**
```bash
git clone <repository-url>
cd printing-system
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install NPM dependencies**
```bash
npm install --legacy-peer-deps
npm run build
```

4. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure database**
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=printing_system
DB_USERNAME=root
DB_PASSWORD=your_password
```

6. **Create database**
```bash
mysql -u root -p
CREATE DATABASE printing_system;
exit;
```

7. **Run migrations and seeders**
```bash
php artisan migrate --seed
```

8. **Start the development server**
```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Default Users

After running seeders, you can login with:

**Admin Account:**
- Email: `admin@printing.com`
- Password: `password`

**Employee Account:**
- Email: `employee@printing.com`
- Password: `password`

## Roles & Permissions

### Admin Role
- manage-users
- manage-roles
- manage-products
- view-all-invoices
- create-invoices
- view-own-invoices
- generate-reports

### Employee Role
- create-invoices
- view-own-invoices

## Database Structure

### Main Tables

1. **users** - System users
2. **roles** - User roles (admin, employee)
3. **permissions** - Available permissions
4. **products** - Product catalog
5. **invoices** - Customer invoices
6. **invoice_items** - Invoice line items

## API Response Helper

The system includes a unified response helper class located at `app/Helpers/ApiResponse.php` for consistent JSON responses:

```php
ApiResponse::success($data, $message, $code);
ApiResponse::error($message, $errors, $code);
ApiResponse::created($data, $message);
ApiResponse::notFound($message);
```

## Form Request Validation

All controllers use Form Request classes for clean validation:

- `StoreProductRequest` / `UpdateProductRequest`
- `StoreInvoiceRequest` / `UpdateInvoiceRequest`
- `StoreUserRequest` / `UpdateUserRequest`

## PDF Generation

Invoices can be exported as PDF using:
```
GET /invoices/{invoice}/pdf
```

## Query Builder

The system uses Spatie Query Builder for advanced filtering and sorting:

**Example:**
```
GET /products?filter[name]=paper&sort=-price
```

## Error Handling

All controllers implement try-catch blocks for proper error handling and user-friendly error messages.

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── ProductController.php
│   │   ├── UserController.php
│   │   ├── InvoiceController.php
│   │   └── ReportController.php
│   └── Requests/
│       ├── StoreProductRequest.php
│       ├── UpdateProductRequest.php
│       ├── StoreInvoiceRequest.php
│       ├── UpdateInvoiceRequest.php
│       ├── StoreUserRequest.php
│       └── UpdateUserRequest.php
├── Models/
│   ├── User.php
│   ├── Product.php
│   ├── Invoice.php
│   └── InvoiceItem.php
└── Helpers/
    └── ApiResponse.php
```

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

### Clear Cache
```bash
php artisan optimize:clear
```

## Security

- All forms protected with CSRF tokens
- SQL injection prevention through Eloquent ORM
- XSS protection through Blade templating
- Password hashing using bcrypt
- Role-based authorization on all sensitive routes

## License

This project is proprietary software developed for Qeematech.

## Support

For support or questions, contact: Eng. Mohamed Abdelrahman
