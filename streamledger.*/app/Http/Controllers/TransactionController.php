<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\KafkaProducer;
use App\Support\TransactionData;
use App\Services\TransactionService;
use App\Resources\TransactionResource;


class TransactionController extends Controller
{
  public function index(Request $request)
  {
    $perPage = intval($request->query('limit', 10));

    $user = $request->user();

    $transactions = (new TransactionService(new KafkaProducer()))->getTransactions($user, $perPage);

    return response()->json([
      'data' => TransactionResource::collection($transactions)->resolve(),
      'meta' => [
        'current_page' => $transactions->currentPage(),
        'last_page' => $transactions->lastPage(),
        'per_page' => $transactions->perPage(),
        'total' => $transactions->total(),
      ],
    ], 200);
  }
  /**
   * @param Request $request
   *
   * @return JsonResponse
   * @throws \Throwable
   */
  public function initiate(Request $request): TransactionResource
  {
    $user = $request->user();

    $data = $request->validate([
      'amount' => 'required|numeric',
      'entry' => 'required|in:credit,debit',
      'idempotencyKey' => 'string|nullable',
    ]);

    $transactionData = TransactionData::createFromRequest($data);

    $transaction = (new TransactionService(new KafkaProducer()))->initiateTransaction($user, $transactionData);

    return new TransactionResource($transaction);
  }
}
