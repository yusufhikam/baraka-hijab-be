<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
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
            'stock' => $this->stock,
            'size' => $this->size,
            'color' => $this->color,
            'weight' => $this->weight,
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}