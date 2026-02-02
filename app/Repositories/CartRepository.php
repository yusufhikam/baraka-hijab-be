<?php  

namespace App\Repositories;

use App\Models\Cart;
use App\Repositories\Interfaces\CartRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CartRepository implements CartRepositoryInterface{

    // get all carts by auth user
    public function getCartsByUserId(int $userId){
        $carts = Cart::with([
                            // 'user', 
                            'productVariantOption:id,product_variant_id,size,stock,is_ready',
                            'productVariantOption.productVariant:id,product_id,name,color,weight',
                            'productVariantOption.productVariant.product:id,name,slug,thumbnail,price', 
                            // 'productVariantOption.productVariant.productVariantOptions:id,product_variant_id,size,stock,is_ready',
                            
                        ])
                        ->where('user_id', $userId)
                        ->latest()
                        ->get();
                        
        return $carts;
    }

    public function getCartByProductVariantOptionId( int $userId,int $productVariantOptionId){
         // todo: cek apakah product_variant_id sudah ada di cart
        $cart = Cart::where('user_id', $userId)
                    ->where('product_variant_option_id', $productVariantOptionId)
                    ->first();

        return $cart;
    }


    public function getCartById(int $userId, int $cartId){
        return Cart::where('user_id', $userId)
                    ->where('id', $cartId)
                    ->firstOrFail();
    }
    // store cart data by auth user
    public function storeCart(array $data, int $userId){

        $cart = $this->getCartByProductVariantOptionId($userId,$data['product_variant_option_id']);

         // jika product_variant_id sudah ada di cart maka tambahkan quantity
        if ($cart) {
            $cart->increment('quantity', $data['quantity']);
            // $cart->quantity += $data['quantity'];
            // $cart->save();
        }
        // jika product_variant_id belum ada di cart maka tambahkan data baru
        else {

            $cart = Cart::create([
                'user_id' => $userId,
                'product_variant_option_id' => $data['product_variant_option_id'],
                'quantity' => $data['quantity'],
            ]);
        }
        
        return $cart;
    }

    // update cart data by auth user
    public function updateCart(array $data, int $userId, int $productVariantOptionId){
        // $cart = $this->getCartByProductVariantOptionId($userId,$productVariantId);
        $cart = $this->getCartByProductVariantOptionId($userId, $productVariantOptionId);

        if($cart){
            $cart->quantity = $data['quantity'];
            $cart->save();
        }

        return $cart;
    }

    // delete cart data by auth user
    public function deleteCartByProductVariantOptionId(int $userId, int $productVariantOptionId)
    {
        // todo: cek apakah product_variant_option_id sudah ada di cart
        // ? menggunakan product variant option id karena saat menggunakan localStorage di client
        // ? cart id tidak disertakan karena sebagai guest user
        $cart = $this->getCartByProductVariantOptionId($userId, $productVariantOptionId);
        $cart->delete();

        return $cart;
    }


    // syncronize cart data from client localStorage to server
    public function syncFromLocalStorage(array $data, int $userId){

        return DB::transaction(function() use ($data, $userId){
            
            $storedCarts = [];

            foreach($data as $item){
                $cart = $this->getCartByProductVariantOptionId($userId,$item['product_variant_option_id']);
        
                if($cart){
                    $cart->increment('quantity', $item['quantity']);
                    $storedCarts[] = $cart;
                    
                } else {
                    $cart = Cart::create([
                        'user_id' => $userId,
                        'product_variant_option_id' => $item['product_variant_option_id'],
                        'quantity' => $item['quantity'],
                    ]);  
                    
                    $storedCarts[] = $cart;
                }
            }

            return $storedCarts;
        });
        
    }
}