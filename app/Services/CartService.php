<?php  

namespace App\Services;

use App\Repositories\CartRepository;
use App\Repositories\Interfaces\CartRepositoryInterface;

class CartService {
    protected $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }


    // get all carts by auth user
    public function getCartsByUserId(int $userId){
        return $this->cartRepository->getCartsByUserId($userId);
    }


    // store cart data by auth user
    public function store(array $data, int $userId){
        return $this->cartRepository->storeCart($data, $userId);
    }

    // update cart data by auth user
    public function update(array $data, int $userId, int $productVariantOptionId){
        return $this->cartRepository->updateCart($data, $userId, $productVariantOptionId);
    }

    // delete cart data by auth user
    public function delete(int $userId, int $cartId){
        return $this->cartRepository->deleteCartByProductVariantOptionId($userId, $cartId);
    }

    // syncronize cart data from client localStorage to server
    public function syncFromLocalStorage(array $data, int $userId){
        return $this->cartRepository->syncFromLocalStorage($data, $userId);
    }
}