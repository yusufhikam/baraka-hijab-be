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

        if($request->routeIs('api.user.transactions')){
            return [
                'id' => $this->id,
                'name' => $this->name,
                'color' => $this->color,
                'product' => new ProductResource($this->whenLoaded('product'))
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->color,
            'weight' => $this->weight,
            'variant_options' => ProductVariantOptionResource::collection($this->whenLoaded('productVariantOptions')),
            'photos' => PhotoResource::collection($this->whenLoaded('photos'))
            // 'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}