<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Services\AddressService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\AddressResource;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{

    public function __construct(protected AddressService $addressService) {}

    public function index()
    {
        $addresses = $this->addressService->getAddressByUserId(Auth::id());

        return AddressResource::collection($addresses);
    }

    // get address by address_id & user_id
    public function show(Address $address){
        $userId = Auth::id();
        $address = $this->addressService->getAddressById($address, $userId);

        return new AddressResource($address);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'provinsi' => 'required|string',
            'kabupaten' => 'required|string',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'detail' => 'required|string|max:255',
            'postal_code' => 'required',
        ]);

        $data = [
            'user_id' => Auth::user()->id,
            'provinsi' => $validated['provinsi'],
            'provinsi_name' => $request->provinsi_name,
            'kabupaten' => $validated['kabupaten'],
            'kabupaten_name' => $request->kabupaten_name,
            'kecamatan' => $validated['kecamatan'],
            'kecamatan_name' => $request->kecamatan_name,
            'kelurahan' => $validated['kelurahan'],
            'kelurahan_name' => $request->kelurahan_name,
            'detail' => $validated['detail'],
            'postal_code' => $validated['postal_code'],
            'is_primary' => false
        ];

        $address = $this->addressService->create($data);

        return response()->json([
            'message' => 'Address created successfully',
            'data' => new AddressResource($address)
        ]);
    }

    public function update(Request $request, Address $address) // model binding for adress(id)
    {
        $validated = $request->validate([
            'provinsi' => 'required|string',
            'kabupaten' => 'required|string',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'postal_code' => 'required|string',
            'detail' => 'required|string|max:255',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'provinsi' => $validated['provinsi'],
            'provinsi_name' => $request->provinsi_name,
            'kabupaten' => $validated['kabupaten'],
            'kabupaten_name' => $request->kabupaten_name,
            'kecamatan' => $validated['kecamatan'],
            'kecamatan_name' => $request->kecamatan_name,
            'kelurahan' => $validated['kelurahan'],
            'kelurahan_name' => $request->kelurahan_name,
            'postal_code' => $validated['postal_code'],
            'detail' => $validated['detail'],
        ];

        $address = $this->addressService->update($address, $data);

        return response()->json([
            'message' => 'Address updated successfully',
            'data' => new AddressResource($address)
        ]);
    }

    // update set primary address
    public function setPrimary(Address $address)
    {
        $data = [
            'user_id' => Auth::id(),
            'is_primary' => true
        ];

        $address = $this->addressService->setPrimary($address, $data);

        return response()->json([
            'message' => 'Address setted to primary is successfully',
            'data' => new AddressResource($address)
        ]);
    }

    // delete an address
    public function destroy(Address $address) // model binding for address(id) 
    {

        $this->addressService->delete($address);
        return response()->json([
            'message' => 'Address deleted successfully'
        ], 200);
    }


    // get primary address 
    public function getPrimaryAddress(){
        $userId = Auth::id();

        $primaryAddress = $this->addressService->getPrimaryAddress($userId);

        if(!$primaryAddress){
            return response()->json([
                'message' => 'Primary address not found'
            ], 404);
        }

        
        return response()->json([
            'message' => 'Successfully get primary address',
            'data' => new AddressResource($primaryAddress)
        ]);    
    }
}