<?php

namespace App\Repositories;

use App\Models\PaymentLog;
use App\Models\ProductVariant;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\String_;

class TransactionRepository implements TransactionRepositoryInterface{
    // create transaction
    public function createTransaction(array $data, array $items): Transaction{
        return DB::transaction(function() use ($data, $items){
            $transaction = Transaction::create($data);

            foreach($items as $item){
                
                $productVariant = $this->findProductVariantById($item['product_variant_id']);

                if(!$productVariant) {
                    throw new \Exception('Product variant not found');
                }

                    // check stock, if stock not enough or 0 transaction canceled
                if($productVariant->stock < $item['quantity']) {
                    throw new \Exception('Stock not enough');
                }

                $productVariant->stock -= $item['quantity'];
                $productVariant->save();

                $item['transaction_id'] = $transaction->id;

                $item['subtotal'] = $item['price'] * $item['quantity'];
                
                
                TransactionItem::create($item);
            }

            return $transaction;
        });
    }

    public function findByOrderIdAndUser(string $orderId, int $userId): Transaction{
        
        return Transaction::where('order_id', $orderId)
                            ->where('user_id', $userId)
                            ->first();
    }           

    public function cancelTransaction( Transaction $transaction): Transaction{
        $transaction->update([
            'status' => 'canceled',
            'canceled_at' => now(),
        ]);
        
        return $transaction;
    }

    public function updatePaymentInfo(Transaction $transaction, array $data): Transaction{
        $transaction->update($data);
        return $transaction;
    }

    // find product_variant_id to update stock
    public function findProductVariantById(int $productVariantId):ProductVariant{
        return ProductVariant::where('id', $productVariantId)->first();
    }
    // find transaction_id
    public function findByOrderId(string $orderId): ?Transaction
    {
        return Transaction::where('order_id', $orderId)->first();
    }

    public function logPayment(Transaction $transaction, array $data, $status){
        return PaymentLog::create([
            'transaction_id' => $transaction->id,
            'raw_response' => json_encode($data),
            'status' => $status
        ]);
    }

    // get 'pending' user transactions
    public function userTransactions(User $user){
        return Transaction::with(['transactionItems.productVariant', 'transactionItems.productVariant.product'])
                            ->where('user_id', $user->id)
                            ->where('status', 'pending')
                            ->latest()
                            ->get(); 
    }

    // get user history transactions [expired, canceled, paid]
    public function userTransactionHistory(User $user){
        return Transaction::with(['transactionItems.productVariant', 'transactionItems.productVariant.product'])
                            ->where('user_id', $user->id)
                            ->where('status', '!=', 'pending')
                            ->latest()
                            ->get();
    }
}