<?php

namespace App\Http\Resources\Api;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
                'name' => $this->name,
                'slug' => $this->slug
            ];
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'subCategories' => SubCategoryResource::collection($this->whenLoaded('subCategories')),
        ];
    }
}