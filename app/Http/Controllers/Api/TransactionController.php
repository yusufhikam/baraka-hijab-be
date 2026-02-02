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
    protected $guard;

    public function __construct(TransactionService $transactionService){
        $this->TransactionService = $transactionService;
        $this->guard = auth('api');

    }

    public function store(Request $request){
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            // 'total_price' => 'required|numeric',
            // 'items' => 'required|array|min:1',
            // 'items.*.product_variant_id' => 'required|exists:product_variants,id',
            // 'items.*.product_variant_option_id' => 'required|exists:product_variant_options,id',
            // // 'items.*.price' => 'required|numeric',
            // 'items.*.quantity' => 'required|integer|min:1',
            // // 'items.*.subtotal' => 'required|numeric',
        ]);

        // get items from session
        $summary = session('checkout.summary');
        
        if(!$summary || !isset($summary['grand_total'])){
            throw new \Exception('Checkout session incomplete or expired');
        }

        $transaction = $this->TransactionService->createTransaction($request, $summary);

        return response()->json(data: [
            'status' => true,
            'message' => 'Transaction created successfully',
            'data' => $transaction
        ])->withoutCookie('checkout_active_session');
    }


    // clear checkout active session after create transaction
    public function clearActiveCheckoutSession(){
        return response()->json([
            'status' => true,
            'message' => 'Checkout session cleared successfully'
        ])->withoutCookie('checkout_active_session');
    }

    public function cancelTransaction(string $orderId){
        try{
            $canceledTransaction = $this->TransactionService->cancelTransaction($orderId);

            return response()->json([
                'status' => true,
                'message' => 'Transaction canceled successfully', 
                'data' => $canceledTransaction
            ], 200)
            ->withoutCookie('checkout_active_session');

        } catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function callback(Request $request){        

        return $this->TransactionService->handleCallback( $request);
    }

    // get user transactions
    public function userTransactions(Request $request){
        $userId = $this->guard->id();
        $request->validate([
            'cursor' => 'nullable|string',
            'per_page' => 'nullable|integer|min:1|max:100',
            'status' => 'nullable|string|in:all,pending,canceled,expire,paid'
        ]);

        $cursor = $request->get('cursor');
        $perPage = $request->get('per_page', 10);
        $status = $request->get('status');

        $transactions = $this->TransactionService->userTransactions($userId, cursor: $cursor, perPage: $perPage, filterStatus: $status);

        
        return response()->json([
            'status' => true,
            'message' => 'Successfully get user transactions',
            'data' => UserTransactionsResource::collection($transactions),
            'meta' => [
                'next_cursor' => $transactions->nextCursor()?->encode(),
                'prev_cursor' => $transactions->previousCursor()?->encode(),
                'has_next' => $transactions->hasMorePages(),
                'per_page' => $transactions->perPage(),
                'current_page_items' => $transactions->count(),
            ]
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


    public function validateTransactionOwnership(string $orderId){
        $userId = $this->guard->id();

        $transaction = $this->TransactionService->validateTransactionOwnership($orderId, $userId);

        if(!$transaction){
            return response()->json([
                'status' => false,
                'message' => 'Transaction is not valid.'
            ], 404);
        }


        return response()->json([
            'status' => true,
            'message' => 'Transaction is valid.',
        ]);
    }
}