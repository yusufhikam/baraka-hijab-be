<?php

namespace App\Http\Resources\api;

use App\Http\Resources\Api\ProductVariantResource;
use App\Http\Resources\Api\SubCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'quantity' => $this->quantity,
            'productVariant' => new ProductVariantResource($this->whenLoaded('productVariant')),
            'user_id' => $this->user_id,
        ];
    }
}