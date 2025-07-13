<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService{

    protected $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository){
        $this->transactionRepository = $transactionRepository;

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
        
    }

    public function getSnapToken(array $payload){
        return Snap::getSnapToken($payload);
    }

    public function handleCallback(array $data){
        $orderId = $data['order_id'] ?? null;

        if(!$orderId){
            throw new \Exception('Invalid transaction, OrderID not found');
        }
        
        $id = str_replace('TXN-','', $orderId);
        $transaction = Transaction::findOrFail($id);
        
        $status = $data['transaction_status'];
        $paymentType = $data['payment_type'];
        $paymentCode = $data['va_numbers'][0]['va_number'] ?? null;
        $grossAmount = $data['gross_amount'];
        $transactionTime = $data['transaction_time'];
        $expiredTime = $data['expiry_time'] ?? null;


        // update transaction
        $transaction->update([
            'payment_type' => $paymentType,
            'payment_status' => $status,
            'payment_code' => $paymentCode,
            'expired_at' => $expiredTime,
            'paid_at' => in_array($status, ['settlement', 'capture']) ? now() : null,
        ]);

        // simpan log pembayaran
        $this
        ->transactionRepository
        ->logPayment(
            $transaction->id,
            $data,
            $status
        );
    }
}