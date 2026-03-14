<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\EmployeeController;


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


Route::resource('employees', EmployeeController::class);

// Extra routes for payments and advances
Route::get('/employees/{employee}/payment', [EmployeeController::class, 'createPayment'])->name('employees.payment.create');
Route::post('/employees/{employee}/payment', [EmployeeController::class, 'storePayment'])->name('employees.payment.store');
Route::get('/employees/{employee}/advance', [EmployeeController::class, 'createAdvance'])->name('employees.advance.create');
Route::post('/employees/{employee}/advance', [EmployeeController::class, 'storeAdvance'])->name('employees.advance.store');

// Salary report route – THIS IS THE MISSING ONE
Route::get('/employees/report/salary', [EmployeeController::class, 'salaryReport'])->name('employees.report.salary');

});

require __DIR__.'/auth.php';