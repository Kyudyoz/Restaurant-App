<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', function () {
    return !auth()->user() ? redirect('/login') : (auth()->user()->role == 'admin' ? redirect('/admin-dashboard') : redirect('/homepage'));
});

Route::middleware(['guest'])->group(function () {
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'newUser'])->name('new-user');
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/admin-dashboard', [AdminController::class, 'index'])->name('dashboard.admin');
    Route::get('/homepage', [CustomerController::class, 'index'])->name('dashboard.user');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('/add-to-cart', [OrderController::class, 'addToCart'])->name('add-to-cart');
    Route::post('/checkout', [CartController::class, 'checkOut'])->name('checkout');
    Route::get('/cart', [OrderController::class, 'index'])->name('cart');
    Route::get('/history', [OrderController::class, 'history'])->name('history');
    Route::delete('/cart/{cartId}', [CartController::class, 'delete'])->name('cart.delete');
});
