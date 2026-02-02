<?php

namespace App\Repositories;

use App\Models\PaymentLog;
use App\Models\ProductVariant;
use App\Models\ProductVariantOption;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\String_;

class TransactionRepository implements TransactionRepositoryInterface{

    // find product_variant_id to update stock
    public function findVariantOptionById(int $productVariantOptionId):ProductVariantOption{
        return ProductVariantOption::with(['productVariant.product'])->findOrFail($productVariantOptionId);
    }

    
    // create transaction
    public function createTransaction(array $data, array $items): Transaction{
        return DB::transaction(function() use ($data, $items){
            $transaction = Transaction::create($data);

            foreach($items as $item){
                
                $variantOption = $this->findVariantOptionById($item['product_variant_option_id']);

                if(!$variantOption) {
                    throw new \Exception('Product variant option not found');
                }

                // check stock, if stock not enough or 0 transaction canceled
                if($variantOption->stock < $item['quantity']) {
                    throw new \Exception('Out of stock');
                }

                // reduce stock
                $variantOption->decrement('stock', $item['quantity']);

                $item['transaction_id'] = $transaction->id;


                $item['price'] = $variantOption->productVariant->product->price;

                $item['subtotal'] = $item['price'] * $item['quantity'];
                
                
                TransactionItem::create($item);
            }

            return $transaction;
        });
    }

    public function findByOrderIdAndUser(string $orderId, int $userId):Transaction{
        
        return Transaction::where('order_id', $orderId)
                            ->where('user_id', $userId)
                            ->first();
    }           

    public function cancelTransaction( Transaction $transaction): Transaction{
        DB::transaction(function () use ($transaction){
            foreach($transaction->transactionItems as $item){
                $option = $item->productVariantOption;

                $option->increment('stock', $item->quantity);
            }
            
            $transaction->update([
                'status' => 'canceled',
                'canceled_at' => now(),
            ]);
        });
        
        return $transaction;
    }

    public function updatePaymentInfo(Transaction $transaction, array $data): Transaction{
        $transaction->update($data);
        return $transaction;
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
    public function userTransactions(Int $userId, ?string $cursor = null, int $perPage = 10, ?string $filterStatus = null): CursorPaginator{
        
        $query = Transaction::with([
                                'transactionItems:id,transaction_id,product_variant_option_id,quantity,subtotal',
                                'transactionItems.productVariantOption:id,size,stock,is_ready,product_variant_id',
                                'transactionItems.productVariantOption.productVariant:id,color,product_id',
                                'transactionItems.productVariantOption.productVariant.product:id,name,thumbnail,price',
                            ])
                            ->where('user_id', $userId)
                            // ->where('status', 'pending')
                            ->latest();

        if(!empty($filterStatus) && $filterStatus !== "all"){
            $query->where('status', $filterStatus);
        }
        
        if($cursor !== null){
            return $query->cursorPaginate($perPage, ['*'], 'cursor', $cursor);
        }

        return $query->cursorPaginate($perPage);
    }

    // get user history transactions [expired, canceled, paid]
    public function userTransactionHistory(User $user){
        return Transaction::with(['transactionItems.productVariantOption', 'transactionItems.productVariantOption.productVariant.product'])
                            ->where('user_id', $user->id)
                            ->where('status', '!=', 'pending')
                            ->latest()
                            ->get();
    }
}