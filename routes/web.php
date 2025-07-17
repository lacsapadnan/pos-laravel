<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('auth.login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notification routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::post('/mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
        Route::get('/count', [NotificationController::class, 'getCount'])->name('count');
    });
});

// Categories routes
Route::middleware(['auth', 'permission:category.view'])->group(function () {
    Route::get('/kategori', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/kategori', [CategoryController::class, 'store'])->middleware('permission:category.store')->name('category.store');
    Route::put('/kategori/{id}', [CategoryController::class, 'update'])->middleware('permission:category.update')->name('category.update');
    Route::delete('/kategori/{id}', [CategoryController::class, 'destroy'])->middleware('permission:category.delete')->name('category.destroy');
});

// Suppliers routes
Route::middleware(['auth', 'permission:supplier.view'])->group(function () {
    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
    Route::post('/supplier', [SupplierController::class, 'store'])->middleware('permission:supplier.store')->name('supplier.store');
    Route::put('/supplier/{id}', [SupplierController::class, 'update'])->middleware('permission:supplier.update')->name('supplier.update');
    Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])->middleware('permission:supplier.delete')->name('supplier.destroy');
});

// Units routes
Route::middleware(['auth', 'permission:unit.view'])->group(function () {
    Route::get('/satuan', [UnitController::class, 'index'])->name('unit.index');
    Route::post('/satuan', [UnitController::class, 'store'])->middleware('permission:unit.store')->name('unit.store');
    Route::put('/satuan/{id}', [UnitController::class, 'update'])->middleware('permission:unit.update')->name('unit.update');
    Route::delete('/satuan/{id}', [UnitController::class, 'destroy'])->middleware('permission:unit.delete')->name('unit.destroy');
});

Route::prefix('konsumen')->group(function () {
    Route::middleware('permission:customer.view')->get('/', [CustomerController::class, 'index'])->name('customer.index');
    Route::middleware('permission:customer.store')->post('/', [CustomerController::class, 'store'])->name('customer.store');
    Route::middleware('permission:customer.update')->put('/{customer}', [CustomerController::class, 'update'])->name('customer.update');
    Route::middleware('permission:customer.delete')->delete('/{customer}', [CustomerController::class, 'destroy'])->name('customer.destroy');
});

// Products routes
Route::middleware(['auth', 'permission:product.view'])->group(function () {
    Route::get('/produk', [ProductController::class, 'index'])->name('product.index');
    Route::get('/produk/create', [ProductController::class, 'create'])->middleware('permission:product.create')->name('product.create');
    Route::post('/produk', [ProductController::class, 'store'])->middleware('permission:product.store')->name('product.store');
    Route::get('/produk/{id}', [ProductController::class, 'show'])->name('product.show');
    Route::get('/produk/{id}/edit', [ProductController::class, 'edit'])->middleware('permission:product.edit')->name('product.edit');
    Route::put('/produk/{id}', [ProductController::class, 'update'])->middleware('permission:product.update')->name('product.update');
    Route::delete('/produk/{id}', [ProductController::class, 'destroy'])->middleware('permission:product.delete')->name('product.destroy');
});

// Stock Management routes
Route::middleware(['auth', 'permission:stock.view'])->prefix('stok')->group(function () {
    Route::get('/', [StockController::class, 'index'])->name('stock.index');
    Route::get('/riwayat', [StockController::class, 'movements'])->name('stock.movements');

    // Stock In
    Route::get('/masuk', [StockController::class, 'stockInForm'])->middleware('permission:stock.in')->name('stock.in.form');
    Route::post('/masuk', [StockController::class, 'stockIn'])->middleware('permission:stock.in')->name('stock.in');

    // Stock Out
    Route::get('/keluar', [StockController::class, 'stockOutForm'])->middleware('permission:stock.out')->name('stock.out.form');
    Route::post('/keluar', [StockController::class, 'stockOut'])->middleware('permission:stock.out')->name('stock.out');

    // Stock Adjustment
    Route::get('/penyesuaian', [StockController::class, 'adjustmentForm'])->middleware('permission:stock.adjustment')->name('stock.adjustment.form');
    Route::post('/penyesuaian', [StockController::class, 'adjustment'])->middleware('permission:stock.adjustment')->name('stock.adjustment');
});

Route::prefix('user')->group(function () {
    Route::middleware('permission:user.view')->get('/', [UserController::class, 'index'])->name('user.index');
    Route::middleware('permission:user.store')->post('/', [UserController::class, 'store'])->name('user.store');
    Route::middleware('permission:user.update')->put('/{user}', [UserController::class, 'update'])->name('user.update');
    Route::middleware('permission:user.delete')->delete('/{user}', [UserController::class, 'destroy'])->name('user.destroy');
});

Route::prefix('role')->group(function () {
    Route::middleware('permission:role.view')->get('/', [RoleController::class, 'index'])->name('role.index');
    Route::middleware('permission:role.update')->get('/{role}/edit', [RoleController::class, 'edit'])->name('role.edit');
    Route::middleware('permission:role.update')->put('/{role}', [RoleController::class, 'update'])->name('role.update');
});

// Sales routes
Route::middleware(['auth'])->prefix('penjualan')->group(function () {
    Route::get('/', [SalesController::class, 'index'])->name('sales.pos');
    Route::get('/riwayat', [SalesController::class, 'history'])->name('sales.history');
    Route::get('/{id}', [SalesController::class, 'show'])->name('sales.show');
    Route::get('/{id}/struk', [SalesController::class, 'receipt'])->name('sales.receipt');
    Route::get('/{id}/cetak', [SalesController::class, 'printReceipt'])->name('sales.print');
    Route::post('/{id}/batal', [SalesController::class, 'cancel'])->name('sales.cancel');

    // Cart API routes
    Route::prefix('keranjang')->group(function () {
        Route::post('/tambah', [SalesController::class, 'addToCart'])->name('sales.cart.add');
        Route::put('/update', [SalesController::class, 'updateCart'])->name('sales.cart.update');
        Route::delete('/hapus', [SalesController::class, 'removeFromCart'])->name('sales.cart.remove');
        Route::get('/data', [SalesController::class, 'getCart'])->name('sales.cart.get');
        Route::delete('/kosongkan', [SalesController::class, 'clearCart'])->name('sales.cart.clear');
    });

    Route::post('/checkout', [SalesController::class, 'checkout'])->name('sales.checkout');
    Route::get('/ringkasan-hari-ini', [SalesController::class, 'todaySummary'])->name('sales.today');
});

// Purchase routes
Route::middleware(['auth', 'permission:purchase.view'])->prefix('pembelian')->group(function () {
    Route::get('/', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/create', [PurchaseController::class, 'create'])->middleware('permission:purchase.create')->name('purchases.create');
    Route::post('/', [PurchaseController::class, 'store'])->middleware('permission:purchase.store')->name('purchases.store');
    Route::get('/{id}', [PurchaseController::class, 'show'])->middleware('permission:purchase.show')->name('purchases.show');
    Route::delete('/{id}', [PurchaseController::class, 'destroy'])->middleware('permission:purchase.delete')->name('purchases.destroy');
});

// Settings routes
Route::middleware(['auth'])->prefix('pengaturan')->group(function () {
    Route::get('/', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/', [SettingController::class, 'update'])->name('settings.update');
});

require __DIR__ . '/auth.php';
