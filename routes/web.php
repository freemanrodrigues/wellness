<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\BrandController;
use App\Http\Controllers\Dashboard\HealthConcernController;
use App\Http\Controllers\Dashboard\{ScraperController, VendorProductManagementController};

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\CheckoutController;


Route::get('/test', [MainController::class, 'test'])->name('test');

Route::get('/', [MainController::class, 'home'])->name('home');
Route::get('/index', [MainController::class, 'home'])->name('home');
Route::get('/about', [MainController::class, 'about'])->name('about');
Route::get('/privacy', [MainController::class, 'privacy'])->name('privacy');
Route::get('/terms', [MainController::class, 'terms'])->name('terms');
Route::get('/contact', [MainController::class, 'contact'])->name('contact');

/*
Route::get('/product-listing', [ProductController::class, 'productListing'])->name('product-listing');
Route::get('/category/{slug}', [ProductController::class, 'productListing'])
    ->name('category.products'); */
Route::get('/category/{category}', [ProductController::class, 'productListing'])
    ->name('product-listing.category');
Route::get('/category/{category}/{subcategory}', [ProductController::class, 'productSubListing'])
    ->name('product-listing.subcategory');
Route::get('/health/{slug}', [ProductController::class, 'productListingByHealthConcern'])
    ->name('health.products');

Route::get('/brand/{slug}', [ProductController::class, 'productListingByBrand'])
    ->name('brand.products');



Route::get('/product/product-details/{id}', [ProductController::class, 'productDetails'])->name('product-details');
Route::post('/cart/add', [BasketController::class, 'store'])->name('cart.add');
Route::get('/cart', [BasketController::class, 'index'])->name('cart.index');
Route::patch('/cart/{id}', [BasketController::class, 'updateQty'])->name('cart.update');
Route::delete('/cart/{id}', [BasketController::class, 'destroy'])->name('cart.destroy');
Route::post('/cart/promo', [BasketController::class, 'applyPromo'])->name('cart.promo');
Route::delete('/cart/promo', [BasketController::class, 'removePromo'])->name('cart.promo.remove');

// Checkout routes (Protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout/shipping', [CheckoutController::class, 'shipping'])->name('checkout.shipping');
    Route::post('/checkout/shipping', [CheckoutController::class, 'storeShipping'])->name('checkout.shipping.store');
    Route::get('/checkout/payment', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/payment', [CheckoutController::class, 'storePayment'])->name('checkout.payment.store');
    Route::get('/checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
});




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
        Route::get('products/import', [ProductController::class, 'importForm'])->name('products.import');
        Route::post('products/import', [ProductController::class, 'importProcess'])->name('products.import.process');
        Route::get('products/sample-csv', [ProductController::class, 'downloadSampleCsv'])->name('products.sample-csv');
        Route::get('get-subcategories/{parentId}', [ProductController::class, 'getSubcategories'])->name('get-subcategories');
        Route::resource('products', ProductController::class);
        Route::resource('category', CategoryController::class);
        Route::post('category/{id}/restore', [CategoryController::class, 'restore'])->name('category.restore');
        Route::resource('brand', BrandController::class);
        Route::post('brand/{id}/restore', [BrandController::class, 'restore'])->name('brand.restore');
        Route::resource('health-concern', HealthConcernController::class);
        Route::post('health-concern/{id}/restore', [HealthConcernController::class, 'restore'])->name('health-concern.restore');
        Route::get('vpm/getvendorprice', [ScraperController::class, 'getVendorPrice'])->name('vpm.getvendorprice');

        Route::resource('vpm', VendorProductManagementController::class);
        Route::post('vpm/{id}/restore', [VendorProductManagementController::class, 'restore'])->name('vpm.restore');
        Route::any('vpm/updateprice', [ScraperController::class, 'updatePrice'])->name('vpm.updateprice');

    });





});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


});

require __DIR__ . '/auth.php';
