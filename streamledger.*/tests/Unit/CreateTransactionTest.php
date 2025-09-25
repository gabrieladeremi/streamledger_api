<?php

use App\Actions\CreateTransaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('credits and updates wallet balance', function () {
  $user = User::factory()->create();
  
  Wallet::factory()->create([
    'user_id' => $user->id,
    'balance_cents' => 0,
  ]);

  $action = app(CreateTransaction::class);
  $tx = $action->handle($user, 'credit', 10.50);

  expect($tx)->toBeInstanceOf(Transaction::class);
  expect($tx->amount_cents)->toBe(1050);
  expect($tx->balance_cents)->toBe(1050);

  $wallet = $user->wallet()->first();
  expect($wallet->balance_cents)->toBe(1050);
});

it('prevents debit when insufficient funds', function () {
  $user = User::factory()->create();
  Wallet::factory()->create([
    'user_id' => $user->id,
    'balance_cents' => 500, // $5
  ]);

  $action = app(CreateTransaction::class);

  $this->expectException(Exception::class);
  $action->handle($user, 'debit', 10.00);
});
