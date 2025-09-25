<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wallet extends Model
{
  /** @use HasFactory<\Database\Factories\WalletFactory> */
  use HasFactory;

  protected $fillable = [
    'user_id',
    'entry',
    'amount_cents',
    'balance_cents',
    'idempotency_key',
  ];

  protected $casts = [
    'amount_cents' => 'integer',
    'balance_cents' => 'integer',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
