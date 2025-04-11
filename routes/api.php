<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminToko\RedeemController;

Route::post('/cek-sn', [RedeemController::class, 'cekSN']);