<?php

namespace App\Repositories\Interfaces;

use App\Models\Address;
use Illuminate\Database\Eloquent\Collection;

interface AddressRepositoryInterface
{
    // get all addresses
    public function getAddressByUserId(int $userId): Collection;

    // get address by id
    public function getAddressById(int $addressId, int $userId): ?Address;
    // Create a new address
    public function create(array $data): Address;

    // update an existing address
    public function update(Address $address, array $data): Address;

    // set primary address
    public function setPrimary(int $userId, int $addressId);

    // delete an address
    public function delete(int $userId, int $addressId);

    // get primary address
    public function getPrimaryAddress(int $userId);

}