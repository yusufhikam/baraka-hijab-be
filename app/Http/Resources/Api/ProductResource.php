<?php

namespace App\Http\Resources\Api;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
                'thumbnail' => $this->thumbnail,
                'price' => $this->price,
                'slug' => $this->slug
            ];
        }

        if ($request->routeIs('api.carousel')) {
            return [
                'id' => $this->id,
                'thumbnail' => $this->thumbnail,
                'name' => $this->name,
                'slug' => $this->slug,
                'product_variants' => ProductVariantResource::collection($this->whenLoaded('productVariants'))
            ];
        };

        if($request->routeIs('api.products.index')){
            return [
                'id' => $this->id,
                'name' => $this->name,
                'thumbnail' => $this->thumbnail,
                'slug' => $this->slug,
                'price' => $this->price,
                'sub_category' => new SubCategoryResource($this->whenLoaded('subCategory')),
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'thumbnail' => $this->thumbnail,
            'slug' => $this->slug,
            'price' => $this->price,
            'variant_stock' => $this->whenLoaded('productVariants', function(){
                return $this->productVariants
                            ->flatMap(fn($variant) => $variant->productVariantOptions)
                            ->sum('stock');
            }),
            'description' => $this->description,
            'sub_category' => new SubCategoryResource($this->whenLoaded('subCategory')),
            'product_variants' => ProductVariantResource::collection($this->whenLoaded('productVariants')),
        ];
    }
}