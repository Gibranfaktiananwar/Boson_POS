<?php

use App\Http\Controllers\AdminToko\CartController;
use App\Http\Controllers\AdminToko\CategoryController;
use App\Http\Controllers\AdminToko\HistoryTransactionController;
use App\Http\Controllers\AdminToko\ProductController;
use App\Http\Controllers\MasterAdmin\DashboardController;
use App\Http\Controllers\MasterAdmin\DataUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminToko\RedeemController;
use App\Http\Controllers\AdminToko\SalesController;
use App\Http\Controllers\masteradmin\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['role:masteradmin'])->group(function () {
        Route::resource('users', DataUserController::class);
        Route::resource('role', RoleController::class)->except(['show', 'create', 'edit']);
        Route::post('permissions', [RoleController::class, 'storePermission'])->name('permissions.store');
    });

    // Claim rewwards
    Route::get('/redeem-rewards', [RedeemController::class, 'index'])->name('admintoko.index');
    Route::post('/generate-token', [RedeemController::class, 'generateToken'])->name('token.generate');
    Route::post('/check-sn', [RedeemController::class, 'checkSerial'])->name('serial.check');

    Route::get('/history-transaction', [HistoryTransactionController::class, 'index'])->name('historytransaction.index');

    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');

    // for products
    Route::resource('products', ProductController::class);
    Route::get('/products-management', [ProductController::class, 'management'])->name('products.management');

    // cart
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::get('cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    Route::resource('category', CategoryController::class);
});

require __DIR__ . '/auth.php';
