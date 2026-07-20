# 📱 Phone Shop POS Application (Laravel)

A **Point-of-Sale (POS) management system** for a phone retail business, built with **Laravel 12**. The application provides the core back-office foundation for a multi-branch shop — authentication, role-based access control, inventory, and invoice configuration — with the POS sales terminal and related modules planned for the next development phase.

---

## 🚀 Features

### 👤 Authentication
- User Registration
- User Login
- Logout
- Email verification (Laravel Breeze)

### 🔐 Role-Based Access Control (RBAC)
- Custom roles with per-page permissions (`page.access` middleware)
- Role management (create, edit, assign)
- User management under Settings

### 🏬 Branch Management
- Manage multiple shop branches from Settings

### 📦 Inventory Management
- Category management (CRUD)
- Product management (CRUD) with cost price and selling price tracking
- Admin-only access using middleware

### 🧾 Invoice Settings
- Configurable invoice prefix and sequential numbering
- Tax rate and currency configuration
- Custom invoice footer note

---

## 🛠 Technologies Used

- **Laravel 12**
- **PHP 8.2**
- **MySQL**
- **Laravel Breeze (Blade + Alpine)**
- **Tailwind CSS**
- **XAMPP**

---

## 🗺 Roadmap (Planned / Coming Soon)

These modules are scaffolded in the routes but not yet implemented:

- 🧮 **POS Terminal** — main point-of-sale checkout/billing screen (`/pos`)
- 📊 **Product Stock** — stock level tracking
- 🙋 **Customer Management** — customer creation & list
- 📈 **Sales Reports**
- 💸 **Expenses**
- 🔔 **Notifications**

---

## 📂 Project Structure (Important Folders)

```
app/
 ├── Http/
 │   ├── Controllers/
 │   │   ├── PhoneController.php
 │   │   ├── Inventory/
 │   │   │   ├── CategoryController.php
 │   │   │   └── ProductController.php
 │   │   └── Settings/
 │   │       ├── RoleController.php
 │   │       ├── UserController.php
 │   │       ├── BranchController.php
 │   │       └── InvoiceSettingController.php
 │   └── Middleware/
 │       └── AdminMiddleware.php
resources/
 ├── views/
 │   ├── dashboard.blade.php
 │   ├── welcome.blade.php
 │   ├── inventory/
 │   ├── settings/
 │   └── admin/
 │       └── phones/
routes/
 └── web.php
database/
 ├── migrations/
 │   ├── create_phones_table.php
 │   ├── add_role_to_users_table.php
 │   ├── create_branches_table.php
 │   ├── create_roles_table.php
 │   ├── create_invoice_settings_table.php
 │   ├── create_categories_table.php
 │   └── add_cost_price_to_phones_table.php
```

---

## ⚙️ Installation Guide

### 1️⃣ Clone the Repository
```bash
git clone <repository-url>
cd phone-shop
```

### 2️⃣ Install Dependencies
```bash
composer install
npm install
npm run build
```

### 3️⃣ Environment Setup
Copy `.env.example` to `.env`

```bash
cp .env.example .env
```

Update database settings in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=phone_shop
DB_USERNAME=root
DB_PASSWORD=
```

### 4️⃣ Generate App Key
```bash
php artisan key:generate
```

---

## 🗄 Database Setup

### Run Migrations
```bash
php artisan migrate
```

### Add Admin Role to User
```sql
UPDATE users
SET role = 'admin'
WHERE email = 'your_email@gmail.com';
```

---

## ▶️ Run the Application

```bash
php artisan serve
```

Visit:
```
http://127.0.0.1:8000
```

---

## 🔐 Admin Middleware (Laravel 12)

Middleware is registered in:

📄 `bootstrap/app.php`

```php
->withMiddleware(function ($middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ]);
})
```

---

## 🔁 Login Redirect

After login, users are redirected to:

```
/dashboard
```

---

## 📌 Notes

- Blade controls UI visibility based on role/permissions
- Middleware controls access security (`admin`, `page.access:<key>`)
- MVC pattern is strictly followed
- Laravel 12 uses `bootstrap/app.php` instead of `Kernel.php`

---

## 👨‍💻 Author

**Malindu Dissanayaka**
Undergraduate Software Engineering Student
Sri Lanka 🇱🇰

---

## 📜 License

This project is for **educational purposes only**.
