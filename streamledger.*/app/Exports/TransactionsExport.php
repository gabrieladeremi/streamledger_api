<?php

namespace App\Exports;

use App\Models\Transaction;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class TransactionsExport implements FromQuery, WithMapping, WithHeadings, WithChunkReading
{
  protected User $user;

  public function __construct(User $user)
  {
    $this->user = $user;
  }
  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    return Transaction::where('user_id', $this->user->id)
      ->orderBy('created_at', 'desc')
      ->get();
  }

  public function query()
  {
    return Transaction::query()
      ->where('user_id', $this->user->id)
      ->orderBy('created_at', 'desc');
  }

  public function chunkSize(): int
  {
    return 1000; // process 1000 rows at a time
  }

  public function map($transaction): array
  {
    return [
      $transaction->id,
      $transaction->entry,
      number_format($transaction->amount_cents / 100, 2, '.', ''),
      number_format($transaction->balance_cents / 100, 2, '.', ''),
      $transaction->created_at->toDateTimeString(),
    ];
  }

  public function headings(): array
  {
    return ['ID', 'Entry', 'Amount', 'Balance', 'Created At'];
  }
}
