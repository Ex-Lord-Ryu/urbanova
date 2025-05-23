<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExampleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\DescriptionTemplateController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CartController;

// Landing and shop pages
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/about', [LandingController::class, 'about'])->name('about');
Route::get('/contact', [LandingController::class, 'contact'])->name('contact');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/added-modal', [CartController::class, 'addedModal'])->name('cart.added-modal');

// Product Detail Route - Public Access
Route::get('/product/{product}', [App\Http\Controllers\ProductController::class, 'show'])->name('product.show');

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changepassword'])->name('profile.change-password');
    Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');

    Route::get('/hakakses', [App\Http\Controllers\HakaksesController::class, 'index'])->name('hakakses.index')->middleware('admin');
    Route::get('/hakakses/create', [App\Http\Controllers\HakaksesController::class, 'create'])->name('hakakses.create')->middleware('admin');
    Route::post('/hakakses/store', [App\Http\Controllers\HakaksesController::class, 'store'])->name('hakakses.store')->middleware('admin');
    Route::get('/hakakses/edit/{id}', [App\Http\Controllers\HakaksesController::class, 'edit'])->name('hakakses.edit')->middleware('admin');
    Route::put('/hakakses/update/{id}', [App\Http\Controllers\HakaksesController::class, 'update'])->name('hakakses.update')->middleware('admin');
    Route::delete('/hakakses/delete/{id}', [App\Http\Controllers\HakaksesController::class, 'destroy'])->name('hakakses.delete')->middleware('admin');

    // Manajemen Kategori Produk - Individual Routes
    Route::middleware(['admin'])->group(function () {
        // Kategori Routes
        Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [App\Http\Controllers\CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}', [App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');
        Route::get('/categories/{category}/edit', [App\Http\Controllers\CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');

        // Produk Routes
        Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [App\Http\Controllers\ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}', [App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
        Route::get('/products/{product}/edit', [App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');

        // Warna Routes
        Route::get('/colors', [ColorController::class, 'index'])->name('colors.index');
        Route::get('/colors/create', [ColorController::class, 'create'])->name('colors.create');
        Route::post('/colors', [ColorController::class, 'store'])->name('colors.store');
        Route::get('/colors/{color}/edit', [ColorController::class, 'edit'])->name('colors.edit');
        Route::put('/colors/{color}', [ColorController::class, 'update'])->name('colors.update');
        Route::delete('/colors/{color}', [ColorController::class, 'destroy'])->name('colors.destroy');

        // Ukuran Routes
        Route::get('/sizes', [SizeController::class, 'index'])->name('sizes.index');
        Route::get('/sizes/create', [SizeController::class, 'create'])->name('sizes.create');
        Route::post('/sizes', [SizeController::class, 'store'])->name('sizes.store');
        Route::get('/sizes/{size}/edit', [SizeController::class, 'edit'])->name('sizes.edit');
        Route::put('/sizes/{size}', [SizeController::class, 'update'])->name('sizes.update');
        Route::delete('/sizes/{size}', [SizeController::class, 'destroy'])->name('sizes.destroy');

        // Settings Routes
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/toggle/{key}', [SettingsController::class, 'toggle'])->name('settings.toggle');
        Route::post('/settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');
        Route::get('/settings/initialize', [SettingsController::class, 'initializeSettings'])->name('settings.initialize');

        // Discount Management Routes
        Route::get('/discounts', [App\Http\Controllers\Admin\DiscountController::class, 'index'])->name('admin.discounts.index');
        Route::get('/discounts/create', [App\Http\Controllers\Admin\DiscountController::class, 'create'])->name('admin.discounts.create');
        Route::post('/discounts', [App\Http\Controllers\Admin\DiscountController::class, 'store'])->name('admin.discounts.store');
        Route::get('/discounts/{id}/edit', [App\Http\Controllers\Admin\DiscountController::class, 'edit'])->name('admin.discounts.edit');
        Route::put('/discounts/{id}', [App\Http\Controllers\Admin\DiscountController::class, 'update'])->name('admin.discounts.update');
        Route::delete('/discounts/{id}', [App\Http\Controllers\Admin\DiscountController::class, 'destroy'])->name('admin.discounts.destroy');
        Route::get('/discounts/bulk', [App\Http\Controllers\Admin\DiscountController::class, 'createBulk'])->name('admin.discounts.create-bulk');
        Route::post('/discounts/bulk', [App\Http\Controllers\Admin\DiscountController::class, 'storeBulk'])->name('admin.discounts.store-bulk');
    });

    // Tambahkan rute-rute AJAX untuk warna dan ukuran
    Route::post('/colors/ajax-store', [App\Http\Controllers\Admin\ColorController::class, 'ajaxStore'])->name('colors.ajax.store');
    Route::post('/sizes/ajax-store', [App\Http\Controllers\Admin\SizeController::class, 'ajaxStore'])->name('sizes.ajax.store');

    // Routes Template Deskripsi
    Route::resource('description-templates', DescriptionTemplateController::class);
    Route::get('get-templates-json', [DescriptionTemplateController::class, 'getTemplatesJson'])->name('description-templates.json');
    Route::post('attach-template-to-product', [DescriptionTemplateController::class, 'attachToProduct'])->name('description-templates.attach');
    Route::post('detach-template-from-product', [DescriptionTemplateController::class, 'detachFromProduct'])->name('description-templates.detach');

    Route::post('/checkout/process', 'CheckoutController@process')->name('checkout.process');
});
