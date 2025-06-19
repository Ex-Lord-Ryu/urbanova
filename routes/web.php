<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExampleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\DescriptionTemplateController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentProofController;

// Landing and shop pages
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/about', [LandingController::class, 'about'])->name('about');
Route::get('/contact', [LandingController::class, 'contact'])->name('contact');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');

// Halaman yang dikonfigurasi dari settings
Route::get('/faq', [LandingController::class, 'faq'])->name('faq');
Route::get('/terms', [LandingController::class, 'terms'])->name('terms');
Route::get('/privacy', [LandingController::class, 'privacy'])->name('privacy');
Route::get('/returns', [LandingController::class, 'returns'])->name('returns');
Route::get('/shipping', [LandingController::class, 'shipping'])->name('shipping');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/added-modal', [CartController::class, 'addedModal'])->name('cart.added-modal');

// Get product variants for product detail
Route::get('/product/get-variants', [App\Http\Controllers\ProductController::class, 'getVariants'])->name('product.getVariants');

// Product Detail Route - Public Access
Route::get('/product/{slug}', [App\Http\Controllers\ProductController::class, 'show'])->name('product.show');

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
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

    // Landing Profile Routes (publicly accessible but requires authentication)
    Route::get('user/profile/profile', [LandingController::class, 'profile'])->name('landing.profile');
    Route::get('user/profile/edit', [LandingController::class, 'editProfile'])->name('landing.profile.edit');
    Route::put('user/profile/update', [LandingController::class, 'updateProfile'])->name('landing.profile.update');

    // User Orders Routes
    Route::get('user/orders', [LandingController::class, 'orders'])->name('landing.orders');
    Route::get('user/orders/{orderNumber}', [LandingController::class, 'orderDetail'])->name('landing.orders.detail');

    // Payment Proof Routes
    Route::get('payment/{orderNumber}/upload', [PaymentProofController::class, 'showUploadForm'])->name('payment.form');
    Route::post('payment/{orderNumber}/upload', [PaymentProofController::class, 'uploadProof'])->name('payment.upload');

    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

    // Manajemen Kategori Produk - Individual Routes
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        // Kategori Routes
        Route::get('categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/create', [App\Http\Controllers\CategoryController::class, 'create'])->name('categories.create');
        Route::post('categories', [App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
        Route::get('categories/{category}', [App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');
        Route::get('categories/{category}/edit', [App\Http\Controllers\CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('categories/{category}', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');

        // Order Management Routes
        Route::get('orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
        Route::post('orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
        Route::post('orders/{order}/payment-status', [App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('admin.orders.update-payment');
        Route::post('orders/{order}/tracking', [App\Http\Controllers\Admin\OrderController::class, 'updateTrackingNumber'])->name('admin.orders.update-tracking');
        Route::delete('orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('admin.orders.destroy');

        // Payment Management Routes
        Route::get('payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('admin.payments.index');
        Route::get('payments/{order}', [App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('admin.payments.show');
        Route::post('payments/{order}/verify', [App\Http\Controllers\Admin\PaymentController::class, 'verify'])->name('admin.payments.verify');
        Route::post('payments/{order}/reject', [App\Http\Controllers\Admin\PaymentController::class, 'reject'])->name('admin.payments.reject');
        Route::get('payments/{order}/download', [App\Http\Controllers\Admin\PaymentController::class, 'download'])->name('admin.payments.download');

        // Produk Routes
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
        Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Product Variants Routes
        Route::get('product-variants', [ProductVariantController::class, 'index'])->name('product-variants.index');
        Route::get('product-variants/create', [ProductVariantController::class, 'create'])->name('product-variants.create');
        Route::post('product-variants', [ProductVariantController::class, 'store'])->name('product-variants.store');
        Route::get('product-variants/{productVariant}/edit', [ProductVariantController::class, 'edit'])->name('product-variants.edit');
        Route::put('product-variants/{productVariant}', [ProductVariantController::class, 'update'])->name('product-variants.update');
        Route::delete('product-variants/{productVariant}', [ProductVariantController::class, 'destroy'])->name('product-variants.destroy');

        // Size-Specific Product Variants Routes
        Route::get('products/{productId}/size-variants/create', [ProductVariantController::class, 'createSizeVariant'])->name('product-variants.create-size-variant');
        Route::get('products/{productId}/size-variants/{sizeId}', [ProductVariantController::class, 'createSizeVariant']);
        Route::post('products/{productId}/size-variants', [ProductVariantController::class, 'storeSizeVariant'])->name('product-variants.store-size-variant');

        // Unified Product Variants Route (new)
        Route::get('product-variants/unified', [ProductVariantController::class, 'createUnified'])->name('product-variants.create-unified');

        // Warna Routes
        Route::get('colors', [ColorController::class, 'index'])->name('colors.index');
        Route::get('colors/create', [ColorController::class, 'create'])->name('colors.create');
        Route::post('colors', [ColorController::class, 'store'])->name('colors.store');
        Route::get('colors/{color}/edit', [ColorController::class, 'edit'])->name('colors.edit');
        Route::put('colors/{color}', [ColorController::class, 'update'])->name('colors.update');
        Route::delete('colors/{color}', [ColorController::class, 'destroy'])->name('colors.destroy');

        // Ukuran Routes
        Route::get('sizes', [SizeController::class, 'index'])->name('sizes.index');
        Route::get('sizes/create', [SizeController::class, 'create'])->name('sizes.create');
        Route::post('sizes', [SizeController::class, 'store'])->name('sizes.store');
        Route::get('sizes/{size}/edit', [SizeController::class, 'edit'])->name('sizes.edit');
        Route::put('sizes/{size}', [SizeController::class, 'update'])->name('sizes.update');
        Route::delete('sizes/{size}', [SizeController::class, 'destroy'])->name('sizes.destroy');

        // Settings Routes
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/toggle/{key}', [SettingsController::class, 'toggle'])->name('settings.toggle');
        Route::post('settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');
        Route::get('settings/initialize', [SettingsController::class, 'initializeSettings'])->name('settings.initialize');
        Route::post('settings/about', [SettingsController::class, 'updateAboutPage'])->name('settings.update-about');
        Route::post('settings/page-content', [SettingsController::class, 'updatePageContent'])->name('settings.update-page-content');

        // Discount Management Routes
        Route::get('discounts', [App\Http\Controllers\Admin\DiscountController::class, 'index'])->name('admin.discounts.index');
        Route::get('discounts/create', [App\Http\Controllers\Admin\DiscountController::class, 'create'])->name('admin.discounts.create');
        Route::post('discounts', [App\Http\Controllers\Admin\DiscountController::class, 'store'])->name('admin.discounts.store');
        Route::get('discounts/{id}/edit', [App\Http\Controllers\Admin\DiscountController::class, 'edit'])->name('admin.discounts.edit');
        Route::put('discounts/{id}', [App\Http\Controllers\Admin\DiscountController::class, 'update'])->name('admin.discounts.update');
        Route::delete('discounts/{id}', [App\Http\Controllers\Admin\DiscountController::class, 'destroy'])->name('admin.discounts.destroy');
        Route::get('discounts/bulk', [App\Http\Controllers\Admin\DiscountController::class, 'createBulk'])->name('admin.discounts.create-bulk');
        Route::post('discounts/bulk', [App\Http\Controllers\Admin\DiscountController::class, 'storeBulk'])->name('admin.discounts.store-bulk');
    });

    // Tambahkan rute-rute AJAX untuk warna dan ukuran
    Route::post('/colors/ajax-store', [App\Http\Controllers\Admin\ColorController::class, 'ajaxStore'])->name('colors.ajax.store');
    Route::post('/sizes/ajax-store', [App\Http\Controllers\Admin\SizeController::class, 'ajaxStore'])->name('sizes.ajax.store');

    // Routes Template Deskripsi
    Route::resource('description-templates', DescriptionTemplateController::class);
    Route::get('get-templates-json', [DescriptionTemplateController::class, 'getTemplatesJson'])->name('description-templates.json');
    Route::post('attach-template-to-product', [DescriptionTemplateController::class, 'attachToProduct'])->name('description-templates.attach');
    Route::post('detach-template-from-product', [DescriptionTemplateController::class, 'detachFromProduct'])->name('description-templates.detach');
});

// Sales Reports Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/sales-reports', 'App\Http\Controllers\Admin\SalesReportController@index');
    Route::get('/sales-reports/export', 'App\Http\Controllers\Admin\SalesReportController@export');
    Route::get('/sales-reports/detailed', 'App\Http\Controllers\Admin\SalesReportController@detailedReport');
    Route::get('/sales-reports/export-detailed', 'App\Http\Controllers\Admin\SalesReportController@exportDetailed');
});
