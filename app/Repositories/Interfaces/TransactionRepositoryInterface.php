<?php

namespace App\Repositories\Interfaces;

use App\Models\ProductVariant;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

interface TransactionRepositoryInterface{
    public function createTransaction(array $data, array $items): Transaction;
    
    public function findByOrderIdAndUser(string $orderId, int $userId): Transaction;
    public function cancelTransaction( Transaction $transaction): Transaction;
    public function updatePaymentInfo(Transaction $transaction, array $data): Transaction;
    public function findByOrderId(string $orderId): ?Transaction;
    public function findProductVariantById(int $productVariantId): ProductVariant;

    public function userTransactions(User $user);

    public function userTransactionHistory(User $user);


    
}