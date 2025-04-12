<?php

use App\Http\Controllers\AdminToko\RedeemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;

Route::post('/generate-token', [RedeemController::class, 'generateToken']);
Route::post('/check-serial', [RedeemController::class, 'checkSerial']);
