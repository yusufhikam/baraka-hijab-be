<?php

namespace App\Services;

use App\Models\Address;
use App\Repositories\Interfaces\AddressRepositoryInterface;

class AddressService
{

    public function __construct(protected AddressRepositoryInterface $addressRepository) {}

    public function getAddressByUserId($userId) // get all addresses by user_id for index
    {
        return $this->addressRepository->getAddressByUserId($userId);
    }

    public function getAddressById(Address $address, int $userId){
        return $this->addressRepository->getAddressById($address, $userId);
    }
    public function create(array $data)
    {
        $first_address = Address::where('user_id', $data['user_id'])->count() === 0;

        $data['is_primary'] = $first_address;

        return $this->addressRepository->create($data);
    }


    // ✅ update an existing address
    public function update(Address $address, array $data)
    {
        return $this->addressRepository->update($address, $data);
    }

    // update set Primary address
    public function setPrimary(Address $address, array $data)
    {
        // set all addresses is_primary to false
        Address::where('user_id', $data['user_id'])->update(['is_primary' => false]);

        // set primary address only one
        return $this->addressRepository->setPrimary($address, $data);
    }

    // delete an address
    public function delete(Address $address)
    {
        return $this->addressRepository->delete($address);
    }

    // get primary address
    public function getPrimaryAddress(int $userId){
        return $this->addressRepository->getPrimaryAddress($userId);
    }
}