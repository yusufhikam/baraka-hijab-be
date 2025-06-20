<?php

namespace App\Repositories\Interfaces;

interface TransactionRepositoryInterface{
    public function create(array $data);
    public function findById(int $id);

    public function storeTransactionItems(int $transactionId, array $data);
    public function logPayment(int $transactionId, string $raw, string $status);
}