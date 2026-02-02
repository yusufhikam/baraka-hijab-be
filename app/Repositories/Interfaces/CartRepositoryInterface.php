<?php  

namespace App\Repositories\Interfaces;


interface CartRepositoryInterface {

    // get all carts by auth user
    public function getCartsByUserId(int $userId);

    // get data cart by product variant id
    public function getCartByProductVariantOptionId(int $userId,int $productVariantOptionId);
    // store cart data by user id
    public function storeCart(array $data, int $userId);

    // syncronize cart data from client localStorage to server
    public function syncFromLocalStorage(array $data, int $userId);

    // update cart data by user id
    public function updateCart(array $data, int $userId, int $productVariantId);
    // delete cart data by product variant id
    public function deleteCartByProductVariantOptionId(int $userId,int $productVariantOptionId);
}