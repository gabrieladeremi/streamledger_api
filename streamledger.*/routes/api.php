<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionExportController;

Route::prefix('v1')->group(function () {
  Route::post('/register', [RegistrationController::class, 'register']);
  Route::post('/login', [LoginController::class, 'login']);

  Route::middleware('auth:sanctum')->group(function () {
    Route::get('/wallet', [WalletController::class, 'show']);

    Route::post('/transactions', [TransactionController::class, 'initiate']);
    Route::get('/transactions', [TransactionController::class, 'index']);

    Route::post('/transactions/export', [TransactionExportController::class, 'export']);
  });

  Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
  });
});
