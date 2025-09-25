<?php

namespace App\Jobs;

use App\Models\TransactionExport;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;


class GenerateTransactionsExportJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  protected int $exportId;

  /**
   * Create a new job instance.
   */
  public function __construct(int $exportId)
  {
    $this->exportId = $exportId;
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    $export = TransactionExport::find($this->exportId);

    if (!$export) {
      return;
    }

    $export->update(['status' => 'processing']);

    $user = $export->user;

    $filename = 'exports/user_' . $user->id . '_' . time() . '.xlsx';
    $fullPath = storage_path('app/' . $filename);

    // Ensure directory exists
    @mkdir(dirname($fullPath), 0755, true);

    Excel::store(new TransactionsExport($user), $filename);

    $export->update([
      'file_path' => $filename,
      'status' => 'done',
    ]);

    \Log::info("Transaction export done for user {$user->id}: {$filename}");
  }

  public function failed(\Throwable $exception)
  {
    $export = TransactionExport::find($this->exportId);
    $export?->update(['status' => 'failed']);
    \Log::error("Transaction export failed for export ID {$this->exportId}", ['error' => $exception->getMessage()]);
  }
}
