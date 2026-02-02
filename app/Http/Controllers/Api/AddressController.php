<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Services\AddressService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\AddressResource;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class AddressController extends Controller
{

    /** @var JWTGuard $guard*/
    protected $guard;

    public function __construct(protected AddressService $addressService) {
        $this->guard = auth('api');
    }

    public function index()
    {
        
        $addresses = $this->addressService->getAddressByUserId($this->guard->id());

        return response()->json([
            'success' => true,
            'message' => 'Successfully get all addresses',
            'data' => AddressResource::collection($addresses)
        ]);
    }

    // get address by address_id & user_id
    public function show(int $addressId){
        $userId = $this->guard->id();
        $address = $this->addressService->getAddressById($addressId, $userId);

        if(!$address){
            return response()->json([
                'success' => false,
                'message' => 'Address not found',
                'data' => null
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully get address',
            'data' => new AddressResource($address)
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'province_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'district_id' => 'required|numeric',
            'subdistrict_id' => 'required|numeric',
            'detail' => 'nullable|string|max:255',
            'postal_code' => 'required|string|max:10',
            'recipient_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'mark_as' => 'required|string|in:home,office,store'
        ]);

        $data = [
            'user_id' => $this->guard->id(),
            'province_id' => $validated['province_id'],
            'province_name' => $request->province_name,
            'city_id' => $validated['city_id'],
            'city_name' => $request->city_name,
            'district_id' => $validated['district_id'],
            'district_name' => $request->district_name,
            'subdistrict_id' => $validated['subdistrict_id'],
            'subdistrict_name' => $request->subdistrict_name,
            'detail' => $validated['detail'],
            'postal_code' => $validated['postal_code'],
            'recipient_name' => $validated['recipient_name'],
            'phone_number' => $validated['phone_number'],
            'mark_as' => $validated['mark_as'],
            'is_primary' => false
        ];

        $address = $this->addressService->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Address created successfully',
            'data' => new AddressResource($address)
        ]);
    }

    public function update(Request $request, Address $address) // model binding for adress(id)
    {
        $validated = $request->validate([
            'province_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'district_id' => 'required|numeric',
            'subdistrict_id' => 'required|numeric',
            'postal_code' => 'required|string',
            'detail' => 'nullable|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'mark_as' => 'required|string|in:home,office,store',
        ]);

        $data = [
            'user_id' => $this->guard->id(),
            'province_id' => $validated['province_id'],
            'province_name' => $request->province_name,
            'city_id' => $validated['city_id'],
            'city_name' => $request->city_name,
            'district_id' => $validated['district_id'],
            'district_name' => $request->district_name,
            'subdistrict_id' => $validated['subdistrict_id'],
            'subdistrict_name' => $request->subdistrict_name,
            'postal_code' => $validated['postal_code'],
            'detail' => $validated['detail'],
            'recipient_name' => $validated['recipient_name'],
            'phone_number' => $validated['phone_number'],
            'mark_as' => $validated['mark_as'],
        ];

        $address = $this->addressService->update($address, $data);

        return response()->json([
            'success' => true,
            'message' => 'Address updated successfully',
            'data' => new AddressResource($address)
        ]);
    }

    // update set primary address
    public function setPrimary(int $addressId)
    {
        $userId = $this->guard->id();

        $address = $this->addressService->setPrimary($userId, $addressId);

        if(!$address){
            return response()->json([
                'success' => false,
                'message' => 'Address not found',
                'data' => null
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Address setted to primary is successfully',
            'data' => new AddressResource($address)
        ]);
    }

    // delete an address
    public function destroy(int $addressId) // model binding for address(id) 
    {
        $userId = $this->guard->id();
        $deleted = $this->addressService->delete($userId, $addressId);

        if(!$deleted){
            return response()->json([
                'success' => false,
                'message' => 'Address not found',
                'data' => null
            ]);
        }


        return response()->json([
            'success' => true,
            'message' => 'Address deleted successfully'
        ], 200);
    }


    // get primary address 
    public function getPrimaryAddress(){
        $userId = $this->guard->id();

        $primaryAddress = $this->addressService->getPrimaryAddress($userId);

        if(!$primaryAddress){
            return response()->json([
                'message' => 'Primary address not found'
            ], 404);
        }

        
        return response()->json([
            'success' => true,
            'message' => 'Successfully get primary address',
            'data' => new AddressResource($primaryAddress)
        ]);    
    }
}