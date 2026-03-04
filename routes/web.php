<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\DashboardController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route for the main menu management view - هذا هو الرابط الرئيسي للصفحة
Route::get('/menu-management', [MenuItemController::class, 'index'])->name('menu.management');

// API-like routes for AJAX requests (using resource for full CRUD operations)
Route::resource('menu-items', MenuItemController::class)->except(['create', 'edit']);

// Optional: Dashboard stats route for AJAX updates
Route::get('/menu-stats', [MenuItemController::class, 'getStats'])->name('menu.stats');

// Optional: If you want to make the menu-management the homepage
// Route::get('/', [MenuItemController::class, 'index'])->name('home');

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
  
    // Make sure the dashboard route uses the DashboardController
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    
// Route for the main menu management view - هذا هو الرابط الرئيسي للصفحة
Route::get('/menu-management', [MenuItemController::class, 'index'])->name('menu.management');

// API-like routes for AJAX requests (using resource for full CRUD operations)
Route::resource('menu-items', MenuItemController::class)->except(['create', 'edit']);

// Optional: Dashboard stats route for AJAX updates
Route::get('/menu-stats', [MenuItemController::class, 'getStats'])->name('menu.stats');

// Optional: If you want to make the menu-management the homepage
// Route::get('/', [MenuItemController::class, 'index'])->name('home');

});

require __DIR__.'/auth.php';
