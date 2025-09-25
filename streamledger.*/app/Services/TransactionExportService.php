<?php

namespace App\Services;

use App\Models\TransactionExport;
use App\Models\User;
use App\Jobs\GenerateTransactionsExportJob;

class TransactionExportService
{
  /**
   * Export transactions for a user
   *
   * @param User $user
   * @return TransactionExport
   * @throws \Throwable
   *
   * @return TransactionExport
   */
  public static function exportTransactions(User $user, string $status = 'pending'): TransactionExport
  {
    $exportedTransaction = TransactionExport::create([
      'user_id' => $user->id,
      'status' => $status,
    ]);

    GenerateTransactionsExportJob::dispatch($exportedTransaction->id);

    return $exportedTransaction;
  }
}
