<?php

namespace App\Services;

use App\Support\TransactionData;
use App\Actions\CreateTransaction;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TransactionService
{
  protected KafkaProducer $kafkaProducer;

  public function __construct(KafkaProducer $kafkaProducer)
  {
    $this->kafkaProducer = $kafkaProducer;
  }

  public function initiateTransaction(User $user, TransactionData $transactionData): Transaction
  {

    $transaction = CreateTransaction::handle(
      $user,
      $transactionData->entry,
      $transactionData->amount,
      $transactionData->idempotencyKey
    );

    $kafkaPayload = [
      'user_id' => $user->id,
      'entry' => $transaction->entry,
      'amount_cents' => $transaction->amount_cents,
      'balance_cents' => $transaction->balance_cents,
      'timestamp' => $transaction->created_at->toIso8601String(),
    ];

    $this->kafkaProducer->produce(json_encode($kafkaPayload));

    return $transaction;
  }

  /**
   * Get paginated transactions for a user
   *
   * @param User $user
   * @param int $perPage
   * @return LengthAwarePaginator
   */
  public function getTransactions(User $user, int $perPage): LengthAwarePaginator
  {
    return Transaction::with('user')
      ->where('user_id', $user->id)
      ->orderBy('created_at', 'desc')
      ->paginate($perPage);
  }
}
