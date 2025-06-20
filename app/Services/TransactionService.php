<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Snap;

class TransactionService{
    protected $transactionRepository;

    public function __construct(TransactionRepositoryInterface $transactionRepository){
        $this->transactionRepository = $transactionRepository;
    }

    public function createTransaction(array $data){
        return DB::transaction(function () use ($data) {
            $transaction = $this->transactionRepository->create([
                'user_id' => Auth::id(),
                'address_id' => $data['address_id'],
                'total_price' => $data['total_price'],
                'status' => 'pending'
            ]);

            $this->transactionRepository->storeTransactionItems(transactionId: $transaction->id, data: $data['items']);

            $snapPayload = [
                'transaction_details'=>[
                    'order_id' => $transaction->id,
                    'gross_amount' => $data['total_price']
                ],
                'customer_details' => [
                    'email' => Auth::user()->email,
                    'first_name' => Auth::user()->name,
                    'phone'=> '+62'. Auth::user()->phone_number,
                ],
                'enabled_payments' => [
                    'bca_va',
                    'bni_va',
                    'bri_va',
                    'gopay',
                    'shopeepay',
                ]
                ];

                $snap = Snap::createTransaction($snapPayload);

                $transaction->update([
                    'snap_token' => $snap->token,
                    'snap_url' => $snap->redirect_url
                ]);

                return $transaction;
        });

    }
}
