<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\DashboardController;



Route::get('/', [MainController::class, 'home'])->name('home');
Route::get('/index', [MainController::class, 'home'])->name('home');
Route::get('/about', [MainController::class, 'about'])->name('about');
Route::get('/privacy', [MainController::class, 'privacy'])->name('privacy');
Route::get('/terms', [MainController::class, 'terms'])->name('terms');
Route::get('/contact', [MainController::class, 'contact'])->name('contact');

Route::get('/product-listing', [ProductController::class, 'productListing'])->name('product-listing');

Route::get('/product-details/{id}', [ProductController::class, 'productDetails'])->name('product-details');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ->prefix('admin')->name('admin.')

Route::middleware(['auth', 'verified', 'admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/index', [DashboardController::class, 'index'])->name('dashboard.index');

    // All other backoffice routes go here, e.g.:
    // Route::resource('orders', OrderController::class);
    // Route::resource('products', ProductController::class);
    // Route::resource('categories', CategoryController::class);
    // Route::resource('customers', CustomerController::class);
    // Dashboard — Products CRUD
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('get-subcategories/{parentId}', [ProductController::class, 'getSubcategories'])->name('get-subcategories');
        Route::resource('products', ProductController::class);
        Route::resource('category', CategoryController::class);
        Route::post('category/{id}/restore', [CategoryController::class, 'restore'])->name('category.restore');
    });


});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


});

require __DIR__ . '/auth.php';
