<?php

namespace App\Repositories;

use App\Models\Address;
use App\Models\User;
use App\Repositories\Interfaces\AddressRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AddressRepository implements AddressRepositoryInterface
{
    // ✅ get all addresses by user_id
    public function getAddressByUserId(int $userId): Collection // get all addresses by user_id
    {
        return Address::where('user_id', $userId)
                        ->orderBy('is_primary' , 'desc')
                        ->latest()
                        ->get();
    }

    // ✅ get address by id
    public function getAddressById(int $addressId, int $userId): ?Address
    {
        return Address::where('id', $addressId)
                        ->where('user_id',$userId)
                        ->first();
    }

    // ✅ store a new address
    public function create(array $data): Address
    {

        return Address::create($data);
    }

    // ✅ update an existing address by address_id
    public function update(Address $address, array $data): Address
    {
        $address->update($data);

        return $address;
    }

    // todo:  ✅ set primary address
    public function setPrimary(int $userId, int $addressId)
    {
        $address = Address::where('user_id', $userId)
                            ->where('id', $addressId)
                            ->first();

        // set primary address only one
        $address->update(['is_primary' => true]);

        // set other address is not primary
        Address::where('user_id', $userId)
                ->where('id', '!=', $addressId)
                ->update(['is_primary' => false]);
        
        $address->refresh();
        

        return $address;
    }

    // ❌ delete an address
    public function delete(int $userId, int $addressId)
    {
        return Address::where("user_id", $userId)
                            ->where("id", $addressId)
                            ->delete();

    }

    // count addresses by user_id, jika add data address pertama kali maka di set primary[true]
    public function countByUserId(User $user)
    {
        return Address::where('user_id', $user->id)->count();
    }

    // get primary address
    public function getPrimaryAddress(int $userId)
    {
        return Address::where('user_id', $userId)
                        ->where('is_primary', true)
                        ->first();
    }
}