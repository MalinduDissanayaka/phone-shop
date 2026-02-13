<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PhoneController;

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

    // 👉 MAIN PAGE AFTER LOGIN (Phone Shop)
    Route::get('/dashboard', [PhoneController::class, 'index'])
        ->name('dashboard');

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
