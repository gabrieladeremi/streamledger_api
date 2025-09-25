<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\TransactionExportService;


class TransactionExportController extends Controller
{
  public function export(Request $request): JsonResponse
  {
    $user = $request->user();

    $export = TransactionExportService::exportTransactions(
      $user,
      'pending'
    );

    return response()->json(['message' => 'Transaction export queued', 'export_id' => $export->id], 202);
  }
}
