<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PhoneController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Settings\InvoiceSettingController;
use App\Http\Controllers\Settings\BranchController;
use App\Http\Controllers\Settings\RoleController;
use App\Http\Controllers\Settings\UserController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Welcome page (first page)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // 👉 MAIN PAGE AFTER LOGIN (POS Dashboard)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Phone storefront (previously the /dashboard page)
    Route::get('/store', [PhoneController::class, 'index'])
        ->name('store');

    // Profile (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Settings Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('settings')->name('settings.')->group(function () {

    Route::middleware('page.access:settings.invoice')->group(function () {
        Route::get('/invoice', [InvoiceSettingController::class, 'edit'])->name('invoice.edit');
        Route::put('/invoice', [InvoiceSettingController::class, 'update'])->name('invoice.update');
    });

    Route::middleware('page.access:settings.branch')->group(function () {
        Route::resource('branches', BranchController::class)->except(['show']);
    });

    Route::middleware('page.access:settings.roles')->group(function () {
        Route::resource('roles', RoleController::class)->except(['show']);
    });

    Route::middleware('page.access:settings.users')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
});

/*
|--------------------------------------------------------------------------
| Coming Soon Placeholder Routes (built out in later phases)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::view('/pos', 'coming-soon', ['title' => 'POS Terminal'])->name('pos.terminal');

    Route::view('/inventory/category', 'coming-soon', ['title' => 'Product Category'])->name('inventory.category');
    Route::view('/inventory/add-product', 'coming-soon', ['title' => 'Add Product'])->name('inventory.add_product');
    Route::view('/inventory/stock', 'coming-soon', ['title' => 'Product Stock'])->name('inventory.stock');

    Route::view('/customers/create', 'coming-soon', ['title' => 'Customer Creation'])->name('customer.create');
    Route::view('/customers', 'coming-soon', ['title' => 'Customer List'])->name('customer.list');

    Route::view('/reports/sales', 'coming-soon', ['title' => 'Sales Report'])->name('report.sales');

    Route::view('/finance/expenses', 'coming-soon', ['title' => 'Expenses'])->name('finance.expenses');

    Route::view('/notifications', 'coming-soon', ['title' => 'Notifications'])->name('notifications.index');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin/phones', [PhoneController::class, 'adminIndex'])
        ->name('admin.phones');

    Route::get('/admin/phones/create', [PhoneController::class, 'create'])
        ->name('admin.phones.create');

    Route::post('/admin/phones', [PhoneController::class, 'store'])
        ->name('admin.phones.store');

    Route::get('/admin/phones/{id}/edit', [PhoneController::class, 'edit'])
        ->name('admin.phones.edit');

    Route::put('/admin/phones/{id}', [PhoneController::class, 'update'])
        ->name('admin.phones.update');

    Route::delete('/admin/phones/{id}', [PhoneController::class, 'destroy'])
        ->name('admin.phones.delete');
});

/*
|--------------------------------------------------------------------------
| Breeze Auth Routes
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\CartController;

Route::middleware(['auth'])->group(function () {

    Route::get('/cart', [CartController::class, 'index'])->name('cart');

    Route::post('/cart/add/{id}', [CartController::class, 'add'])
        ->name('cart.add');

    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])
        ->name('cart.remove');
});


require __DIR__.'/auth.php';
