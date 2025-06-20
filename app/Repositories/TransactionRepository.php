<?php

namespace App\Repositories;

use App\Models\PaymentLog;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use PhpParser\Node\Expr\Cast\String_;

class TransactionRepository implements TransactionRepositoryInterface{
    // create transaction
    public function create(array $data){
        return Transaction::create($data);
    }
    // find transaction_id
    public function findById(int $id)
    {
        return Transaction::with('transactionItems.productVariant')->findOrFail($id);
    }

    // untuk store transactionItems saat create transaction
    public function storeTransactionItems(int $transactionId, array $items){
        foreach($items as $item){
            TransactionItem::create([
                'transaction_id' => $transactionId,
                'product_variant_id' => $item['product_variant_id'],
                'price'=> $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity']
            ]);
        }
    }

    // create paymentLogs saat create transaction
    public function logPayment(Int $transactionId, string $raw, string $status){
        return PaymentLog::create([
            'transaction_id' => $transactionId,
            'raw_response'=> $raw,
            'status' => $status
        ]);
    }
}