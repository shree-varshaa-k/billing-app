<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\VendorController; 
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\PurchaseInvoiceController;

// ----------------------------
// Public Routes
// ----------------------------

// First page — show login form
Route::get('/', [AuthController::class, 'showLoginForm']);
Route::get('/products/barcodes/download', [ProductController::class, 'downloadAllBarcodes'])
    ->name('products.barcodes.download');

// Forgot Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('forgot.password');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('forgot.password.post');

// Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ----------------------------
// Protected Routes (Require Login)
// ----------------------------
Route::middleware(['auth.session'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Invoices
    Route::get('/invoice', [InvoiceController::class, 'invoice'])->name('invoice');
    Route::get('/createinvoice', [InvoiceController::class, 'createinvoice'])->name('createinvoice');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::resource('invoices', InvoiceController::class)->except(['store']);
    Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    Route::get('invoices/{invoice}/preview', [InvoiceController::class, 'preview'])->name('invoices.preview');
    Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');

    // Clients
    Route::resource('clients', ClientController::class);
    Route::post('/clients/check-duplicate', [ClientController::class, 'checkDuplicate'])->name('clients.checkDuplicate');

    // Sales
    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');

    Route::get('/sales/{id}/print', [SalesController::class, 'printInvoice'])->name('sales.print');
    Route::get('/sales/{id}/pos', [SalesController::class, 'posInvoice'])->name('sales.pos');
    Route::delete('/sales/{id}', [SalesController::class, 'destroy'])->name('sales.destroy');

    // Reports
    Route::get('/reports', [ReportsController::class, 'saleReport'])->name('reports.index');
    Route::get('/sales', [ReportsController::class, 'saleReport']);

    Route::prefix('reports')->group(function () {

        Route::get('/', function () {
            return redirect()->route('reports.sales');
        })->name('reports.index');

        Route::get('/sales', [ReportsController::class, 'saleReport'])->name('reports.sales');
        Route::get('/profit-loss', [ReportsController::class, 'profitLoss'])->name('reports.profitloss');
    });

    // PRODUCT MASTER ROUTES
    Route::prefix('products')->group(function () {

        Route::get('/categories', [CategoryController::class, 'index'])->name('products.categories');
        Route::post('/categories/store', [CategoryController::class, 'store'])->name('products.categories.store');
        Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('products.categories.update');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('products.categories.delete');

        Route::get('/subcategories', [SubCategoryController::class, 'index'])->name('products.subcategories');
        Route::post('/subcategories/store', [SubCategoryController::class, 'store'])->name('products.subcategories.store');
        Route::put('/subcategories/{id}', [SubCategoryController::class, 'update'])->name('products.subcategories.update');
        Route::delete('/subcategories/{id}', [SubCategoryController::class, 'destroy'])->name('products.subcategories.delete');

        Route::get('/sizes', [SizeController::class, 'index'])->name('products.sizes');
        Route::post('/sizes/store', [SizeController::class, 'store'])->name('products.sizes.store');
        Route::put('/sizes/{id}', [SizeController::class, 'update'])->name('products.sizes.update');
        Route::delete('/sizes/{id}', [SizeController::class, 'destroy'])->name('products.sizes.delete');

        Route::get('/brands', [BrandController::class, 'index'])->name('products.brands');
        Route::post('/brands/store', [BrandController::class, 'store'])->name('products.brands.store');
        Route::put('/brands/{id}', [BrandController::class, 'update'])->name('products.brands.update');
        Route::delete('/brands/{id}', [BrandController::class, 'destroy'])->name('products.brands.delete');
    });

    Route::resource('products', ProductController::class)->except(['show']);

    Route::get('/get-product-stock/{id}', [ProductController::class, 'getStock'])->name('products.getStock');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // ----------------------------
    // Purchases Routes
    // ----------------------------

    Route::resource('vendors', VendorController::class);

    Route::prefix('purchases')->group(function () {

        Route::get('invoices', [PurchaseInvoiceController::class, 'index'])->name('purchase.invoice.index');
        Route::get('invoices/create', [PurchaseInvoiceController::class, 'create'])->name('purchase.invoice.create');
        Route::post('/purchase-invoice/store', [PurchaseInvoiceController::class, 'store'])->name('purchase.invoice.store');

        // ✅ NEW REQUIRED ROUTE (Fixes your error)
        Route::post('/', [PurchaseInvoiceController::class, 'store'])->name('purchases.store');

        Route::get('invoices/{id}/edit', [PurchaseInvoiceController::class, 'edit'])->name('purchase.invoice.edit');
        Route::put('invoices/{id}', [PurchaseInvoiceController::class, 'update'])->name('purchase.invoice.update');
        Route::get('/purchase-invoice/{id}', [PurchaseInvoiceController::class, 'show'])->name('purchase.invoice.show');
        Route::get('invoices/{id}/download', [PurchaseInvoiceController::class, 'download'])->name('purchase.invoice.download');
        Route::delete('invoices/{id}', [PurchaseInvoiceController::class, 'destroy'])->name('purchase.invoice.delete');

        Route::get('vendors', [VendorController::class, 'index'])->name('purchases.vendors.index');
        Route::get('vendors/create', [VendorController::class, 'create'])->name('purchases.vendors.create');
        Route::post('vendors', [VendorController::class, 'store'])->name('purchases.vendors.store');
        Route::get('vendors/{vendor}/edit', [VendorController::class, 'edit'])->name('purchases.vendors.edit');
        Route::put('vendors/{vendor}', [VendorController::class, 'update'])->name('purchases.vendors.update');
        Route::delete('vendors/{vendor}', [VendorController::class, 'destroy'])->name('purchases.vendors.destroy');
    });

    Route::get('invoice/pdf/{id}', [PurchaseInvoiceController::class, 'downloadPDF'])
        ->name('purchase.invoice.pdf');
});
