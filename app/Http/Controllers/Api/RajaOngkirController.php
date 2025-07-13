<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    public function searchDestination(Request $request)
    {

        $response = Http::withHeaders([
            'key' => config('rajaongkir.api_key')
        ])->get("https://rajaongkir.komerce.id/api/v1/destination/domestic-destination", [
            'search' => $request->search,
            'limit' => 10,
            'offset' => 0
        ]);


        return response()->json($response->json(['data']));
    }

    public function cekOngkir(Request $request)
    {

        $response = Http::withHeaders([
            'key' => config('rajaongkir.api_key')
        ])->asForm()->post("https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost", [
            'origin' => $request->origin,
            'destination' => $request->destination,
            'weight' => $request->weight,
            'courier' => $request->courier,
            'price' => $request->price,
        ]);


        return response()->json($response->json());
    }

    public function getProvinsi()
    {
        $response = Http::get("https://wilayah.id/api/provinces.json");

        return response()->json($response->json());
    }

    public function getKabupaten($codeProvince)
    {
        $response = Http::get("https://wilayah.id/api/regencies/$codeProvince.json");

        return response()->json($response->json());
    }

    public function getKecamatan($codeKabupaten)
    {
        $response = Http::get("https://wilayah.id/api/districts/$codeKabupaten.json");

        return response()->json($response->json());
    }

    public function getKelurahan($codeKecamatan)
    {
        $response = Http::get("https://wilayah.id/api/villages/$codeKecamatan.json");

        return response()->json($response->json());
    }

    // biteship_test.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoiYmFyYWthLWhpamFiIiwidXNlcklkIjoiNjgzYjAyOTM5MjI3NzgwMDEyZDg4YmEyIiwiaWF0IjoxNzQ4Njk4NTc0fQ.KXy_MQs_frvgTaBCB1EBfIRI6fko5zBY5toDRJF_3ys
}