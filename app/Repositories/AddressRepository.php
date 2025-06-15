<?php

namespace App\Repositories;

use App\Models\Address;
use App\Repositories\Interfaces\AddressRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AddressRepository implements AddressRepositoryInterface
{
    // ✅ get all addresses by user_id
    public function getAddressByUserId(int $userId): Collection // get all addresses by user_id
    {
        return Address::where('user_id', $userId)->orderBy('is_primary' , 'desc')->latest()->get();
    }

    // ✅ get address by id
    public function getAddressById(Address $address, int $userId): Address
    {
        return Address::where('id', $address->id)->where('user_id',$userId)->first();
    }

    // ✅ create a new address
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

    // set primary address
    public function setPrimary(Address $address, array $data): Address
    {
        $address->update($data);

        return $address;
    }

    // ❌ delete an address
    public function delete(Address $address): Address
    {
        $address->delete();

        return $address;
    }

    // count addresses by user_id, jika add data address pertama kali maka di set primary[true]
    public function countByUserId(Address $address): Address
    {
        return Address::where('user_id', $address->user_id)->count();
    }

    // get primary address
    public function getPrimaryAddress(int $userId)
    {
        return Address::where('user_id', $userId)
                        ->where('is_primary', true)
                        ->first();
    }
}