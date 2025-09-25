<?php

namespace App\Support;

class TransactionData
{
  public function __construct(
    public readonly string $amount,
    public readonly string $entry,
    public readonly ?string $idempotencyKey = null
  ) {}

  /**
   * @param array $request
   *
   * @return static
   * @throws \Throwable
   */
  public static function createFromRequest(array $request): static
  {
    $amount = $request['amount'];

    $entry = $request['entry'];

    $idempotencyKey = $request['idempotencyKey'];

    throw_if(
      ! isset($amount, $entry),
      new \UnexpectedValueException('One or more required inputs were not provided')
    );

    return new static(
      amount: $amount,
      entry: $entry,
      idempotencyKey: $idempotencyKey
    );
  }
}
