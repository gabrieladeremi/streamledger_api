<?php

namespace App\Support;

use Illuminate\Http\Request;

class UserRegistrationData
{
  public function __construct(
    public readonly string $name,
    public readonly string $email,
    public readonly string $password
  ) {}

  /**
   * @param Mixed $request
   *
   * @return static
   * @throws \Throwable
   */
  public static function createFromRequest(Mixed $request): static
  {
    $name = $request['name'];

    $email = $request['email'];

    $password = $request['password'];

    throw_if(
      ! isset($name, $email, $password),
      new \UnexpectedValueException('One or more required inputs were not provided')
    );

    return new static(
      name: $name,
      email: $email,
      password: $password
    );
  }
}
