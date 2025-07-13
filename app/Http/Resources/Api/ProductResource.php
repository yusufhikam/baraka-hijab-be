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

        if ($request->routeIs('api.carousel')) {
            return [
                'id' => $this->id,
                'thumbnail' => $this->thumbnail,
                'name' => $this->name,
            ];
        };

        return [
            'id' => $this->id,
            'name' => $this->name,
            'thumbnail' => $this->thumbnail,
            'slug' => $this->slug,
            'price' => $this->price,
            'is_ready' => $this->is_ready,
            'description' => $this->description,
            'subCategory' => new SubCategoryResource($this->whenLoaded('subCategory')),
            'productVariants' => ProductVariantResource::collection($this->whenLoaded('productVariants')),
            'photos' => PhotoResource::collection($this->whenLoaded('photos'))
        ];
    }
}