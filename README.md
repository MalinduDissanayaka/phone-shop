# 📱 Phone Shop Application (Laravel)

A simple **Phone Shop web application** built with **Laravel 12**, designed for beginners to understand Laravel fundamentals such as routing, authentication, middleware, MVC, and MySQL integration.

---

## 🚀 Features

### 👤 Authentication
- User Registration
- User Login
- Logout
- Email verification (Laravel Breeze)

### 🛍 Phone Shop (User)
- View phone list
- View phone details (image, description, price)
- Accessible only after login

### 🔐 Admin Panel
- Admin-only access using middleware
- Add new phones
- Edit phone details
- Delete phones
- Admin actions hidden from normal users

---

## 🛠 Technologies Used

- **Laravel 12**
- **PHP 8.2**
- **MySQL**
- **Laravel Breeze (Blade + Alpine)**
- **Tailwind CSS**
- **XAMPP**

---

## 📂 Project Structure (Important Folders)

```
app/
 ├── Http/
 │   ├── Controllers/
 │   │   └── PhoneController.php
 │   └── Middleware/
 │       └── AdminMiddleware.php
resources/
 ├── views/
 │   ├── dashboard.blade.php
 │   ├── welcome.blade.php
 │   └── admin/
 │       └── phones/
routes/
 └── web.php
database/
 ├── migrations/
 │   └── create_phones_table.php
 │   └── add_role_to_users_table.php
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

Which shows the **Phone Shop page**.

---

## 🧪 Dummy Data (Phones)

```sql
INSERT INTO phones (name, description, price, image, created_at, updated_at) VALUES
('iPhone 14', 'Apple iPhone with A15 chip', 289000, 'iphone14.jpg', NOW(), NOW()),
('Samsung Galaxy S23', 'AMOLED flagship phone', 245000, 'galaxy_s23.jpg', NOW(), NOW());
```

---

## 📌 Notes for Beginners

- Blade controls UI visibility (`@if(auth()->user()->role === 'admin')`)
- Middleware controls access security
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
