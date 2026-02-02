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
            'quantity' => (int) $this->quantity,
            'product_variant_option_id' => (int) $this->productVariantOption->id,
            'product' => [
                'id' => $this->productVariantOption->productVariant->product->id,
                'name' => $this->productVariantOption->productVariant->product->name,
                'slug' => $this->productVariantOption->productVariant->product->slug,
                'thumbnail' => $this->productVariantOption->productVariant->product->thumbnail,
                'price' => $this->productVariantOption->productVariant->product->price,
            ] ,
            'product_variant' => new ProductVariantResource(optional($this->productVariantOption)->productVariant),
            'variant_option' => new ProductVariantOptionResource(optional($this->productVariantOption)),
        ];
    }
}