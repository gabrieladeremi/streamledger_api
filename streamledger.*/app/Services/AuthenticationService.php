<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class AuthenticationService
{
  public static function authenticateUser(
    string $email,
    string $password
  ): array {
    $credentials = [
      'email' => $email,
      'password' => $password
    ];

    throw_if(!Auth::attempt($credentials), new \Exception('Invalid credentials', 401));

    $user = Auth::user();

    $token = $user->createToken('authToken')->plainTextToken;

    return [
      'user' => $user,
      'token' => $token
    ];
  }
}
