<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\PaymentLog;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction as MidtransTransaction;

class TransactionService{
    protected $transactionRepository;
    protected $midtransService;

    public function __construct(TransactionRepositoryInterface $transactionRepository){
        $this->transactionRepository = $transactionRepository;

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
        
    }

    public function createTransaction(Request $request){

        //    simpan transaction
        $transaction = $this->transactionRepository->createTransaction([
            'user_id' => Auth::id(),
            'address_id' => $request->address_id,
            'total_price' => $request->total_price,
            'status' => 'pending',
            'expired_at' => now()->addMinutes(60)
        ], $request->items);
        
        
        $transaction->order_id = 'BRK-HJB-' . $transaction->id;      
        $transaction->save();

        // delete cart data if transaction is pending
        if($transaction->status === 'pending'){
            Cart::where('user_id', Auth::id())->delete();
        }
        
        // payload midtrans
        $payload = [
            'transaction_details'=>[
                'order_id' => $transaction->order_id,
                'gross_amount' => $transaction->total_price
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => '0'.Auth::user()->phone_number
            ], 
            
        ];
        
        // create snap midtrans
        $snap = Snap::createTransaction($payload);
        
        // simpan snap token & URL ke db
        $this->transactionRepository->updatePaymentInfo($transaction, [
            'snap_token' => $snap->token,
            'snap_url' => $snap->redirect_url
        ]);
        
        

        return $transaction->fresh(); // return lengkap termasuk snap_url
    }

    public function cancelTransaction(string $orderId): Transaction{
        $user = Auth::user();
        $transaction = $this->transactionRepository->findByOrderIdAndUser($orderId, $user->id);

        if(!$transaction){
            throw new \Exception('Transaction not found');
        }

        if($transaction->status !== 'pending'){
            throw new \Exception('Only pending transactions can be canceled');
        }

        return $this->transactionRepository->cancelTransaction($transaction);
    }

    public function handleCallback(Request $request){
        try{
            $notif = new \Midtrans\Notification();

            $transaction = $this->transactionRepository->findByOrderId($request->order_id);

            if(!$transaction){
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            // get payment status
            $paymentStatus = $notif->transaction_status;
            $isPaid = in_array($paymentStatus, ['settlement', 'capture']);
            $isExpired = $paymentStatus === 'expire';
            
            // get payment code
            $paymentCode = $notif->va_number[0]->va_numbers ?? $notif->payment_code ?? $notif->bill_key ?? null;
            
            // simpan log payments
            PaymentLog::create([
                'transaction_id' => $transaction->id,
                'raw_response' => json_encode($request->all()),
                'status' => $isPaid
            ]);


            // update status transaction
            $transaction->update([
                'payment_type' => $notif->payment_type,
                'payment_status' => $paymentStatus,
                'payment_code' => $paymentCode,
                'status' => $isExpired ? 'expired' : ($isPaid ? 'paid' : $paymentStatus),
                // 'status' => $isPaid,
                'paid_at' => $isPaid ? now() : null,
            ]);

        

            return response()->json(['message' => 'callback handled'], 200);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Callback error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // get 'pending' user transactions
    public function userTransactions(User $user){
        return $this->transactionRepository->userTransactions($user);
        
    }

    // get user history transactions [expired, canceled, paid]
    public function userTransactionHistory(User $user){
        return $this->transactionRepository->userTransactionHistory($user);
    }
    
}