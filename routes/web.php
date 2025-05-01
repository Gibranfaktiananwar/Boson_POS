<?php

use App\Http\Controllers\AdminToko\CashierController;
use App\Http\Controllers\AdminToko\CatalogController;
use App\Http\Controllers\AdminToko\CategoryController;
use App\Http\Controllers\AdminToko\HistoryTransactionController;
use App\Http\Controllers\MasterAdmin\DashboardController;
use App\Http\Controllers\MasterAdmin\DataUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminToko\RedeemController;
use App\Http\Controllers\AdminToko\SalesController;
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
        Route::get('/users', [DataUserController::class, 'index'])->name('users.index');
        Route::post('/users', [DataUserController::class, 'store'])->name('users.store');
        Route::put('/users/{id}', [DataUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [DataUserController::class, 'destroy'])->name('users.destroy');
    });

    // Claim rewwards
    Route::get('/redeem-rewards', [RedeemController::class, 'index'])->name('admintoko.index');
    Route::post('/generate-token', [RedeemController::class, 'generateToken'])->name('token.generate');
    Route::post('/check-sn', [RedeemController::class, 'checkSerial'])->name('serial.check');


    Route::get('category', [CategoryController::class, 'index'])->name('category.index');
    Route::get('category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('category', [CategoryController::class, 'store'])->name('category.store');
    Route::get('category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('category/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
    Route::get('/history-transaction', [HistoryTransactionController::class, 'index'])->name('historytransaction.index');
    Route::get('/cashier', [CashierController::class, 'index'])->name('cashier.index');

    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
});

require __DIR__ . '/auth.php';
