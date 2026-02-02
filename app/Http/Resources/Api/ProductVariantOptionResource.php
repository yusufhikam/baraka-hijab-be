<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantOptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

    if($request->routeIs('api.user.transactions')){

        return [
            'id' => $this->id,
            'size' => $this->size,
            'stock' => $this->stock,
            'is_ready' => $this->is_ready,
            'product_variant' => new ProductVariantResource($this->whenLoaded('productVariant'))
        ];
    }


        return [
            'id' => $this->id,
            'size' => $this->size,
            'stock' => $this->stock,
            'is_ready' => $this->is_ready
        ];
    }
}