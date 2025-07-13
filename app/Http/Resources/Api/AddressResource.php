<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'provinsi' => $this->provinsi,
            'provinsi_name' => $this->provinsi_name,
            'kabupaten' => $this->kabupaten,
            'kabupaten_name' => $this->kabupaten_name,
            'kecamatan' => $this->kecamatan,
            'kecamatan_name' => $this->kecamatan_name,
            'kelurahan' => $this->kelurahan,
            'kelurahan_name' => $this->kelurahan_name,
            'postal_code' => $this->postal_code,
            'detail' => $this->detail,
            'is_primary' => $this->is_primary
        ];
    }
}