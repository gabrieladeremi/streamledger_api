<?php

use App\Actions\CreateTransaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('creates a transaction via API', function () {
    $user = User::factory()->create();
    Wallet::factory()->create(['user_id' => $user->id, 'balance_cents' => 0]);

    $token = $user->createToken('test')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer '.$token)
        ->postJson('/api/v1/transactions', [
            'entry' => 'credit',
            'amount' => 20.00,
        ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('transactions', [
        'user_id' => $user->id,
        'entry' => 'credit',
    ]);

    $this->assertDatabaseHas('wallets', [
        'user_id' => $user->id,
        'balance_cents' => 2000,
    ]);
});
