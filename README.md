# Printing Shop Management System

A complete printing shop management system built with Laravel 12, featuring role-based access control, invoice management, product catalog, and comprehensive reporting.

**GitHub Repository:** [https://github.com/AhmedHamdy7/Printing-System](https://github.com/AhmedHamdy7/Printing-System)

**Company:** Qeematech
**Developer:** Mustafa Fahmy
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
git clone https://github.com/AhmedHamdy7/Printing-System.git
cd Printing-System
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

## Architecture

This project follows **Clean Code principles** with a **Service Layer** and **Repository Pattern** for better separation of concerns and testability.

### Layered Architecture

```
Controller → Service → Repository → Model → Database
```

**Benefits:**
- **Thin Controllers**: Controllers only handle HTTP requests/responses
- **Reusable Business Logic**: Services can be used across different parts of the application
- **Testable Code**: Easy to unit test services and repositories
- **Maintainable**: Changes to business logic don't affect controllers
- **DRY Principle**: No code duplication

## Project Structure

```
app/
├── Http/
│   ├── Controllers/          # Handle HTTP requests/responses only
│   │   ├── ProductController.php
│   │   ├── UserController.php
│   │   ├── InvoiceController.php
│   │   └── ReportController.php
│   └── Requests/             # Form validation
│       ├── StoreProductRequest.php
│       ├── UpdateProductRequest.php
│       ├── StoreInvoiceRequest.php
│       ├── UpdateInvoiceRequest.php
│       ├── StoreUserRequest.php
│       └── UpdateUserRequest.php
├── Services/                 # Business logic layer
│   ├── InvoiceService.php   # Invoice calculations & operations
│   ├── ReportService.php    # Report generation logic
│   └── PdfService.php       # PDF generation
├── Repositories/             # Data access layer
│   ├── BaseRepository.php   # Base repository with common methods
│   ├── ProductRepository.php
│   ├── InvoiceRepository.php
│   └── UserRepository.php
├── Models/                   # Eloquent models with relationships & scopes
│   ├── User.php
│   ├── Product.php
│   ├── Invoice.php
│   └── InvoiceItem.php
├── Helpers/
│   └── ApiResponse.php
└── Providers/
    └── AppServiceProvider.php  # Service & Repository bindings
```

## Clean Code Features

### 1. Service Layer
Handles all business logic:
```php
// InvoiceService
- calculateInvoiceTotals()    // Calculate subtotal, discount, total
- createInvoice()              // Create invoice with transaction
- updateInvoice()              // Update invoice with transaction
- canUserAccessInvoice()       // Authorization logic
```

### 2. Repository Pattern
Handles all database queries:
```php
// ProductRepository
- getAllWithFilters()          // Get products with filtering & sorting
- getActive()                  // Get only active products

// InvoiceRepository
- getAllWithFilters()          // Get invoices with role-based filtering
- findWithRelations()          // Get invoice with related data
- getForDate()                 // Get invoices for specific date
```

### 3. Model Scopes
Reusable query constraints:
```php
// Product Model
Product::active()->get()       // Get active products
Product::inactive()->get()     // Get inactive products

// Invoice Model
Invoice::forUser($userId)      // Filter by user
Invoice::forDate($date)        // Filter by date
Invoice::betweenDates($start, $end)
Invoice::withRelations()       // Eager load relations
```

### 4. Configuration
Centralized settings in `config/printing.php`:
```php
- pagination_limit             // Default pagination
- invoice.number_format        // Invoice number format
- invoice.pdf_settings         // PDF generation settings
```

## Development

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

---

## Project Information

**GitHub:** [https://github.com/AhmedHamdy7/Printing-System](https://github.com/AhmedHamdy7/Printing-System)

**Company:** Qeematech
**Developer:** Mustafa Fahmy
**Prepared By:** Eng. Mohamed Abdelrahman

**License:** Proprietary software developed for Qeematech

---

For support or questions, contact: **Eng. Mohamed Abdelrahman**


