<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhoneController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', [PhoneController::class, 'index']);

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/phones', [PhoneController::class, 'adminIndex']);
    Route::get('/admin/phones/create', [PhoneController::class, 'create']);
    Route::post('/admin/phones', [PhoneController::class, 'store']);
    Route::get('/admin/phones/{id}/edit', [PhoneController::class, 'edit']);
    Route::put('/admin/phones/{id}', [PhoneController::class, 'update']);
    Route::delete('/admin/phones/{id}', [PhoneController::class, 'destroy']);
});

require __DIR__.'/auth.php';
