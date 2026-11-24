<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WeatherController;

Route::get('/', fn() => redirect()->route('dashboard'));

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Admin Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('members', MemberController::class);
    Route::resource('categories', CategoryController::class)->except(['show', 'create']);
    Route::resource('levels', LevelController::class)->except(['show', 'create']);

    // Transactions & Status Updates
    Route::resource('transactions', TransactionController::class)->only(['index', 'create', 'store', 'show']);
    Route::patch('/transactions/{transaction}/status', [TransactionController::class, 'updateStatus'])->name('transactions.updateStatus');
    Route::get('/transactions/{transaction}/receipt', [TransactionController::class, 'receipt'])->name('transactions.receipt');

    // Payments
    Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store']);

    // Weather API
    Route::get('/weather', [WeatherController::class, 'show'])->name('weather.show');
});