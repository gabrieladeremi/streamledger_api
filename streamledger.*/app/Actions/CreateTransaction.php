<?php

namespace App\Actions;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class CreateTransaction
{
  /**
   * @param \App\Models\User $user
   * @param string $entry 'credit'|'debit'
   * @param float|int $amountDecimal
   * @param string|null $idempotencyKey
   * @return Transaction
   *
   * @throws Exception on insufficient funds or invalid input
   */
  public static function handle(User $user, string $entry, float|int $amountDecimal, ?string $idempotencyKey = null): Transaction
  {
    $amountCents = (int) round($amountDecimal * 100);

    if ($amountCents <= 0) {
      throw new Exception('Amount must be greater than 0');
    }

    if ($idempotencyKey) {
      $existing = Transaction::where('user_id', $user->id)
        ->where('idempotency_key', $idempotencyKey)
        ->first();
      if ($existing) {
        return $existing;
      }
    }

    /** @var Transaction $txn */
    $txn = DB::transaction(function () use ($user, $entry, $amountCents, $idempotencyKey) {
      // Lock wallet row
      $wallet = Wallet::where('user_id', $user->id)->lockForUpdate()->firstOrFail();

      // Update balance
      if ($entry === 'debit') {
        if ($wallet->balance_cents < $amountCents) throw new Exception('Insufficient funds');
        $wallet->balance_cents -= $amountCents;
      } else {
        $wallet->balance_cents += $amountCents;
      }
      $wallet->save();

      // Create transaction row
      return Transaction::create([
        'user_id' => $user->id,
        'entry' => $entry,
        'amount_cents' => $amountCents,
        'balance_cents' => $wallet->balance_cents,
        'idempotency_key' => $idempotencyKey,
      ]);
    }, 5);

    return $txn;
  }
}
