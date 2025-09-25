<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use App\Services\RegistrationService;
use App\Support\UserRegistrationData;


class RegistrationController extends Controller
{
  /**
   * @param Request $request
   *
   * @return JsonResponse
   * @throws \Throwable
   */
  public function register(Request $request): JsonResponse
  {
    $data = $request->validate([
      'email' => 'email|unique:users,email',
      'name' => 'string|unique:users,name',
      'password' => 'string|min:6',
    ]);

    $registrationData = UserRegistrationData::createFromRequest($data);

    $registeredUser = RegistrationService::registerUser($registrationData);

    return response()->json(
      [
        'user' => new UserResource($registeredUser['user']),
        'token' => $registeredUser['token'],
      ],
      201
    );
  }
}
