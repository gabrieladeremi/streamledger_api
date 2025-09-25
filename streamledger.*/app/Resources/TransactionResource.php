<?php

namespace App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   */
  public function toArray(Request $request)
  {
    return [
      'id' => $this->id,
      'amount' => number_format($this->amount_cents / 100, 2, '.', ''),
      'type' => $this->entry, // 'entry' field maps to type
      'balance' => number_format($this->balance_cents / 100, 2, '.', ''),
      'created_at' => $this->created_at?->toDateTimeString(),
      'updated_at' => $this->updated_at?->toDateTimeString(),
      'user' => [
        'id' => $this->user->id,
        'name' => $this->user->name,
        'email' => $this->user->email,
      ],
    ];
  }
}
