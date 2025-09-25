<?php

namespace App\Http\Controllers;

use App\Http\Resources\WalletResource;
use Illuminate\Http\Request;

class WalletController extends Controller
{
  public function show(Request $request)
  {
    $wallet = $request->user()->wallet;

    if (! $wallet) {
      return response()->json([
        'message' => 'Wallet not found.'
      ], 404);
    }

    return new WalletResource($wallet);
  }
};
