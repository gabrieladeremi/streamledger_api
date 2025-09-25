<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
  /**
   * Render an exception into an HTTP response.
   */
  protected $dontReport = [];

  protected $dontFlash = ['current_password', 'password', 'password_confirmation'];

  public function register(): void
  {
    //
  }

  // Override unauthenticated() for API routes
  protected function unauthenticated($request, AuthenticationException $exception)
  {
    return response()->json([
      'message' => 'Unauthenticated.'
    ], 401);
  }

  public function render($request, Throwable $exception)
  {
    // Optionally handle validation errors and other API exceptions here
    return parent::render($request, $exception);
  }
}
