<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionItemsResource extends JsonResource
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
            // 'transaction_id' => $this->transaction_id,
            'product_variant_option' => new ProductVariantOptionResource($this->whenLoaded('productVariantOption')),
            'quantity' => $this->quantity,
            'subtotal' => $this->subtotal
        ];
    }
}