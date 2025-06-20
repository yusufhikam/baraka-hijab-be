<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repositories\TransactionRepository;

class MidtransService{

    protected $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository){
        $this->transactionRepository = $transactionRepository;
    }

    public function handleCallback(array $data){
        $orderId = $data['order_id'];
        $status = $data['transaction_status'];
        $paymentType = $data['payment_type'];
        $paymentCode = $data['va_numbers'][0]['va_number'] ?? null;
        $grossAmount = $data['gross_amount'];
        $transactionTime = $data['transaction_time'];
        $expiredTime = $data['expiry_time'] ?? null;

        $id = str_replace('TXN-','', $orderId);
        $transaction = Transaction::findOrFail($id);

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
            json_encode($data),
            $status
        );
    }
}
