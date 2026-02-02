<?php

namespace App\Repositories\Interfaces;

use App\Models\ProductVariant;
use App\Models\ProductVariantOption;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\CursorPaginator;

interface TransactionRepositoryInterface{
    public function createTransaction(array $data, array $items): Transaction;
    
    public function findByOrderIdAndUser(string $orderId, int $userId);
    public function cancelTransaction( Transaction $transaction): Transaction;
    public function updatePaymentInfo(Transaction $transaction, array $data): Transaction;
    public function findByOrderId(string $orderId): ?Transaction;
    public function findVariantOptionById(int $productVariantOptionId): ProductVariantOption;

    public function userTransactions(int $userId, ?string $cursor = null, int $perPage = 10, ?string $filterStatus = null): CursorPaginator;

    public function userTransactionHistory(User $user);


    
}