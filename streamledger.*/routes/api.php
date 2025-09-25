<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\LoginController;

Route::prefix('v1')->group(function () {
  Route::post('/register', [RegistrationController::class, 'register']);
  Route::post('/login', [LoginController::class, 'login']);

  Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
  });
});
