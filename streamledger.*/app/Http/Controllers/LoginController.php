<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Resources\UserResource;
use App\Services\AuthenticationService;

class LoginController extends Controller
{
  /**
   * @param Request $request
   *
   * @return JsonResponse
   * @throws \Throwable
   */
  public function login(Request $request): JsonResponse
  {
    $credentials = $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    try {
      $authUser = AuthenticationService::authenticateUser($credentials['email'], $credentials['password']);

      return response()->json([
        'user' => new UserResource($authUser['user']),
        'token' => $authUser['token'],
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'message' => $e->getMessage(),
      ], $e->getCode() ?: 400);
    }
  }
}
