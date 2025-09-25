<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
  /** @use HasFactory<\Database\Factories\WalletFactory> */
  use HasFactory;

  protected $fillable = [
    'user_id',
    'balance_cents',
  ];

  protected $casts = [
    'balance_cents' => 'integer',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function transactions()
  {
    return $this->hasMany(Transaction::class);
  }
}
