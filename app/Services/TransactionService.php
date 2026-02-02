<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Cart;
use App\Models\PaymentLog;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction as MidtransTransaction;

class TransactionService{
    protected $transactionRepository;
    protected $midtransService;

    protected $guard;

    public function __construct(TransactionRepositoryInterface $transactionRepository){
        $this->transactionRepository = $transactionRepository;

        $this->guard = auth('api');

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
        
    }

    public function createTransaction(Request $request, array $summary){

        $user  = $this->guard;

        $grandTotal = $summary['grand_total'];
        $items = collect($summary['items']);

        $userAddress = Address::where('id', $request->address_id)->where('user_id', $user->id())->first();

        

        // simpan transaction ke database via repository
        $transaction = $this->transactionRepository->createTransaction([
            'user_id' => $user->id(),
            'address_id' => $request->address_id,
            'total_price' => $grandTotal,
            'status' => 'pending',
        ], $items->toArray());
        
        
        $transaction->order_id = 'BRK-HJB-' . $transaction->id;      
        $transaction->save();

        // delete cart data if transaction is pending and source is cart
        if($transaction->status === 'pending' && session('checkout.source') === 'cart'){
            Cart::where('user_id', $user->id())->delete();
        }
        
        // payload midtrans
        $payload = [
            'transaction_details'=> [
                'order_id' => $transaction->order_id,
                'gross_amount' => $grandTotal
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => '0'.Auth::user()->phone_number,
                'shipping_address' => [
                    'first_name' => Auth::user()->name,
                    'phone' => '0'.Auth::user()->phone_number,
                    'email' => Auth::user()->email,
                    'address' => $userAddress->sub_district_name,
                    'city' => $userAddress->city_name,
                    'postal_code' => $userAddress->postal_code
                ]
            ], 
            'callbacks' => [
                'finish' => 'javascript:void(0)'
            ]
            
        ];
        
        // create snap midtrans
        $snap = Snap::createTransaction($payload);
        
        // simpan snap token & URL ke db
        $this->transactionRepository->updatePaymentInfo($transaction, [
            'snap_token' => $snap->token,
            'snap_url' => $snap->redirect_url,
            // 'expired_at' => now()->addMinutes(60) // fallback sementara sebelum nanti di update dari midtrans di callback
        ]);

        // forget checkout sessions
        session()->forget([
            'checkout.items',
            'checkout.source',
            'checkout.summary',
            'checkout.shipping'
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

    public function handleCallback( Request $request){        
        try{
            $notif = new \Midtrans\Notification();

            $payload = $request->all();

            $transaction = $this->transactionRepository->findByOrderId( $request->order_id);

            if(!$transaction){
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            // get payment status
            $paymentStatus = $notif->transaction_status;
            $isPaid = in_array($paymentStatus, ['settlement', 'capture']);
            $isExpired = $paymentStatus === 'expire';



            $expiryTime = \Carbon\Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $payload['expiry_time'],
                'Asia/Jakarta'
            )->utc();
            
            $expiredAt =  !empty($expiryTime) ? $expiryTime : null; // fallback jika midtrans error

            // get payment code
            $paymentCode = $notif->va_numbers[0]->va_number ?? $notif->payment_code ?? $notif->bill_key ?? null;
            
            // simpan log payments
            PaymentLog::create([
                'transaction_id' => $transaction->id,
                'raw_response' => json_encode($request->all()),
                'status' => $isPaid
            ]);


            // update status transaction
            $updateData = [
                'payment_type' => $notif->payment_type,
                'payment_status' => $paymentStatus,
                'payment_code' => $paymentCode,
                'status' => $isExpired ? 'expire' : ($isPaid ? 'paid' : $paymentStatus),
                // 'status' => $isPaid,
                'paid_at' => $isPaid ? now() : null,
                'expired_at' => $expiredAt
            ];

            // update expired_at jika expiry_time ada
            // if($expiryTime){
            //     $updateData['expired_at'] = $expiryTime;
            // }

            $transaction->update($updateData);
        

            return response()->json(['message' => 'callback handled'], 200);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Callback error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // get 'pending' user transactions
    public function userTransactions(int $userId, ?string $cursor = null, int $perPage = 10, ?string $filterStatus = null){
        return $this->transactionRepository->userTransactions($userId, $cursor, $perPage, $filterStatus);
        
    }

    // get user history transactions [expired, canceled, paid]
    public function userTransactionHistory(User $user){
        return $this->transactionRepository->userTransactionHistory($user);
    }

    // check ownership last transaction
    public function validateTransactionOwnership(string $orderId, int $userId){
        $transaction =  $this->transactionRepository->findByOrderIdAndUser($orderId, $userId);
        return $transaction !== null;
    }
    
}