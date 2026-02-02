<?php 

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaOngkirService{

    protected $TTL = 60 * 60 * 24 * 30; // for 30 days

    public function provinces(): array{
        return $this->fetchAndCache(
            'rajaongkir_provinces',
            'province'
        );
    }

    public function cities(int $provinceId) {
        return $this->fetchAndCache(
            "rajaongkir_cities_$provinceId",
            'city',
            $provinceId
        );
    }

    public function districts(int $cityId):array{
        return $this->fetchAndCache(
            "rajaongkir_districts_$cityId",
            'district',
            $cityId
        );
    }

    public function subDistricts(int $districtId){
        return $this->fetchAndCache(
            "rajaongkir_subdistricts_$districtId",
            'sub-district', // endpoint from docs raja ongkir
            $districtId,
            fn($item) => [
                'id' => $item['id'],
                'name' => $item['name'],
                'zip_code' => $item['zip_code']
            ]
        );
    }

    public function fetchAndCache(string $cacheKey, string $endpoint, ?int $id = null, ?callable $transform = null){
       return Cache::remember($cacheKey, $this->TTL, function() use($endpoint,$id,$transform){
            $url = $id 
                ? "https://rajaongkir.komerce.id/api/v1/destination/$endpoint/$id"
                : "https://rajaongkir.komerce.id/api/v1/destination/$endpoint";

            try{

                $response = Http::withHeaders([
                            'key' => config('rajaongkir.api_key')
                        ])
                        // ->timeout(10)
                        ->get($url);

                if(!$response->successful()){
                    Log::warning('RajaOngkir API errorr', [
                        'endpoint' => $endpoint,
                        'id' => $id,
                        'message' => $response->body()
                    ]);

                    return [];
                }

                $json = $response->json();
                $data = $json['data'] ?? [];

                // use transform function if provided
                if($transform){
                     return collect($data)->map($transform)->values()->toArray();
                }

                // DEFAULT : only return id and name
                return collect($data)
                        ->map(fn($item) => [
                            'id' => $item['id'],
                            'name' => $item['name']
                        ])
                        ->values()
                        ->toArray();

            }catch(Exception $e){
                Log::error("RajaOngkir API timeout/error", [
                    'endpoint' => $endpoint,
                    'id' => $id,
                    'message' => $e->getMessage()
                ]);

                return [];
            }
       });
    }
}