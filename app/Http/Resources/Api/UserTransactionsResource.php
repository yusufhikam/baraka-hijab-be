<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserTransactionsResource extends JsonResource
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
            'address_id' => new AddressResource($this->whenLoaded('address')),
            'order_id' => $this->order_id,
            'transactionItems' => TransactionItemsResource::collection($this->whenLoaded('transactionItems')),
            'status' => $this->status,
            'total_price' => $this->total_price,
            'snap_token' => $this->snap_token,
            'snap_url' => $this->snap_url,
            'paid_at' => $this->paid_at,
            'expired_at' => $this->expired_at,
            'canceled_at' => $this->canceled_at,
            'created_at' => $this->created_at,
            'payment_type' => $this->payment_type,

        ];
    }
}