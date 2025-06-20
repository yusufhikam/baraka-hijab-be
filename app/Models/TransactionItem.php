<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionItem extends Model
{
    protected $fillable = [
        'transaction_id',
        'product_variant_id',
        'price',
        'quantity',
        'subtotal'
    ];

    public function transaction():BelongsTo{
        return $this->belongsTo(Transaction::class);
    }

    public function productVariant():BelongsTo{
        return $this->belongsTo(ProductVariant::class);
    }
}
