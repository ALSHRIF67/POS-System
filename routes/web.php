<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public route - welcome page
Route::get('/', function () {
    return view('welcome');
});

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Optional: make / redirect to dashboard
    Route::get('/home', [DashboardController::class, 'index'])->name('home');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Menu Management
    Route::get('/menu-management', [MenuItemController::class, 'index'])->name('menu.management');
    Route::resource('menu-items', MenuItemController::class)->except(['create', 'edit']);
    Route::get('/menu-stats', [MenuItemController::class, 'getStats'])->name('menu.stats');

 // Orders
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

// ✅ Fixed: separate URIs for show and edit
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');

Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
Route::get('/reports/daily', [OrderController::class, 'dailyReport'])->name('reports.daily');

});

require __DIR__.'/auth.php';