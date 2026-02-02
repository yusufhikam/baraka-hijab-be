<?php

namespace App\Services;

use App\Models\Address;
use App\Repositories\Interfaces\AddressRepositoryInterface;

class AddressService
{

    public function __construct(protected AddressRepositoryInterface $addressRepository) {}

    // * get all addresses by user_id for index
    public function getAddressByUserId($userId) 
    {
        return $this->addressRepository->getAddressByUserId($userId);
    }

    public function getAddressById(int $addressId, int $userId): ?Address
    {
        return $this->addressRepository->getAddressById($addressId, $userId);
    }

    /**
     * * STORE NEW ADDRESS
     */
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
    public function setPrimary(int $userId, int $addressId)
    {
        // set primary address only one
        return $this->addressRepository->setPrimary($userId, $addressId);
    }

    // delete an address
    public function delete(int $userId, int $addressId)
    {
        return $this->addressRepository->delete($userId, $addressId);
    }

    // get primary address
    public function getPrimaryAddress(int $userId){
        return $this->addressRepository->getPrimaryAddress($userId);
    }
}