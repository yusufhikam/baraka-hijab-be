<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $TransactionService;

    public function __construct(TransactionService $transactionService){
        $this->TransactionService = $transactionService;
    }

    public function store(Request $request){
        $validated = $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'items' => 'required|array|min:1',
            'items.*.product_variant_id' => 'required|exists:product_variants,id',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric'
        ]);

        $transaction = $this->TransactionService->createTransaction(data: $validated);

        return response()->json(data: [
            'message' => 'Transaction created successfully',
            'data' => $transaction
        ]);
    }
}
