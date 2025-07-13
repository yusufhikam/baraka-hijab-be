<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserTransactionsResource;
use App\Models\Transaction;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    protected $TransactionService;
    

    public function __construct(TransactionService $transactionService){
        $this->TransactionService = $transactionService;

    }

    public function store(Request $request){
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'total_price' => 'required|numeric',
            'items' => 'required|array|min:1',
            'items.*.product_variant_id' => 'required|exists:product_variants,id',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1',
            // 'items.*.subtotal' => 'required|numeric',
        ]);

        $transaction = $this->TransactionService->createTransaction($request);

        return response()->json(data: [
            'message' => 'Transaction created successfully',
            'data' => $transaction
        ]);
    }

    public function cancelTransaction(string $orderId){
        try{
            $canceledTransaction = $this->TransactionService->cancelTransaction($orderId);

            return response()->json([
                'message' => 'Transaction canceled successfully', 
                'data' => $canceledTransaction
            ], 200);

        } catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function callback(Request $request){
        return $this->TransactionService->handleCallback($request);
    }

    // get user transactions
    public function userTransactions(User $user){
        $user = Auth::user();

        $transaction = $this->TransactionService->userTransactions($user);

        
        return response()->json([
            'message' => 'Successfully get user transactions',
            'data' => UserTransactionsResource::collection($transaction)
        ], 200);
    }

    // get user history transactions [expired. canceled, paid]
    public function userTransactionHistory(User $user){
        $user = Auth::user();

        $transactions = $this->TransactionService->userTransactionHistory($user);
        return response()->json([
            'message' => 'Successfully get user history transactions',
            'data' => UserTransactionsResource::collection($transactions)
        ]);
    }
}