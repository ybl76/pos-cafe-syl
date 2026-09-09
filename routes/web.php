<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\RoleMiddleware;

// Route Auth (Bisa diakses tanpa login)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route yang membutuhkan autentikasi
Route::middleware(['auth'])->group(function () {

    // Akses Admin & Kasir
    Route::get('/', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/store', [POSController::class, 'store'])->name('pos.store');
    Route::get('/pos/receipt/{id}', [POSController::class, 'receipt'])->name('pos.receipt');

    // Khusus Admin
    Route::middleware([RoleMiddleware::class . ':admin'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });

});