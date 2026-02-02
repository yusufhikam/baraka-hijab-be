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
            'province_id' => $this->province_id,
            'province_name' => $this->province_name,
            'city_id' => $this->city_id,
            'city_name' => $this->city_name,
            'district_id' => $this->district_id,
            'district_name' => $this->district_name,
            'subdistrict_id' => $this->subdistrict_id,
            'subdistrict_name' => $this->subdistrict_name,
            'postal_code' => $this->postal_code,
            'recipient_name' => $this->recipient_name,
            'phone_number' => $this->phone_number,
            'mark_as' => $this->mark_as,
            'detail' => $this->detail,
            'is_primary' => $this->is_primary,
            'label' => $this->label
        ];
    }
}