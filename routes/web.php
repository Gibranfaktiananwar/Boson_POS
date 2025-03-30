<?php

use App\Http\Controllers\MasterAdmin\DashboardController;
use App\Http\Controllers\MasterAdmin\DataUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminToko\RedeemController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // can only be accessed by the masteradmin role
    Route::middleware(['role:masteradmin'])->group(function () {
        // Data User
        Route::get('/users', [DataUserController::class, 'index'])->name('users.index');
        Route::post('/users', [DataUserController::class, 'store'])->name('users.store');
        Route::put('/users/{id}', [DataUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [DataUserController::class, 'destroy'])->name('users.destroy');
    });

    Route::get('/redeem-rewards', [RedeemController ::class, 'index'])->name('admintoko.index');
});

require __DIR__ . '/auth.php';
