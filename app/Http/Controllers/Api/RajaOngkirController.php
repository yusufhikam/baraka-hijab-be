<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\ShippingOrigin;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class RajaOngkirController extends Controller
{

    protected $TTL;
    protected $rajaOngkirService;

    /** @var JWTGuard $guard*/
    protected $guard;
    public function __construct(RajaOngkirService $rajaOngkirService)
    {
        $this->guard = auth('api');
        $this->TTL = 60 * 60 * 24 * 30;
        $this->rajaOngkirService = $rajaOngkirService;
    }


    public function searchDestination(Request $request)
    {

        $response = Http::withHeaders([
            'key' => config('rajaongkir.api_key')
        ])->get("https://rajaongkir.komerce.id/api/v1/destination/domestic-destination", [
            'search' => $request->search,
            'limit' => 999,
            'offset' => 0
        ]);


        return response()->json($response->json(['data']));
    }

    public function cekOngkir(Request $request)
    {
        
        $request->validate([
            'destination' => 'required|numeric',
            'weight' => 'required|numeric',
            'courier' => 'required|string',
        ]);

        // $userId = $this->guard->id();
        // dd($userId);

        $origin = ShippingOrigin::where('is_default', true)->first();
        // $customerDestination = Address::where('user_id', $userId)->where('is_primary', true)->first();
        
        $response = Http::withHeaders([
            'key' => config('rajaongkir.api_key')
        ])->asForm()->post("https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost", [
            'origin' => $origin->subdistrict_id,
            'destination' => $request->destination,
            'weight' => $request->weight,
            'courier' => $request->courier,
            'price' => $request->price,
        ]);

        return response()->json($response->json());
    }

    public function getProvinsi()
    {   
        return response()->json([
                'meta' => [
                    "message" => "Successfully get Province",
                    'code' => 200,
                    'status' => "Success"
                ],
                'data' => $this->rajaOngkirService->provinces()
            ]
        );
    }
                    
    public function getKabupaten(int $provinceId)
    {
        // get province from public API wilayah.id
        // $response = Http::get("https://wilayah.id/api/regencies/$codeProvince.json");

        
        // get kabupaten from RAJA ONGKIR
       
                        
        return response()->json([
            'meta' => [
                'message' => 'Successfully get Cities by PROVINCE ID',
                'code' => 200,
                'status' => 'Success',
            ],
            'data' => $this->rajaOngkirService->cities($provinceId)
        ]);
    }

    public function getKecamatan(int $cityId)
    {

        // get province from public API wilayah.id
        // $response = Http::get("https://wilayah.id/api/districts/$codeKabupaten.json");

         // get kecamatan from RAJA ONGKIR

        return response()->json([
            'meta' => [
                'message' => 'Successfully get Districts by CITY ID',
                'code' => 200,
                'status' => 'Success',
            ],
            'data' => $this->rajaOngkirService->districts($cityId)
        ]);
    }

    public function getKelurahan(int $districtId)
    {
        // get province from public API wilayah.id
        // $response = Http::get("https://wilayah.id/api/villages/$codeKecamatan.json");


        return response()->json([
            'meta' => [
                'message' => 'Successfully get Subdistricts by DISTRICT ID',
                'code' => 200,
                'status' => 'Success',
            ],
            'data' => $this->rajaOngkirService->subDistricts($districtId)
        ]);
    }

    // biteship_test.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoiYmFyYWthLWhpamFiIiwidXNlcklkIjoiNjgzYjAyOTM5MjI3NzgwMDEyZDg4YmEyIiwiaWF0IjoxNzQ4Njk4NTc0fQ.KXy_MQs_frvgTaBCB1EBfIRI6fko5zBY5toDRJF_3ys
}