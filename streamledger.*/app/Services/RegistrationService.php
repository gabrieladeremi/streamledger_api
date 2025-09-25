<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;
use App\Support\UserRegistrationData;

class RegistrationService
{
  public static function registerUser(UserRegistrationData $registrationData): array
  {
    $user = User::create([
      'email' => strtolower($registrationData->email),
      'name' => $registrationData->name,
      'password' => Hash::make($registrationData->password),
    ]);

    Wallet::create(['user_id' => $user->id, 'balance_cents' => 0]);

    $token = $user->createToken('api-token')->plainTextToken;

    return [
      'user' => $user,
      'token' => $token,
    ];
  }
}
