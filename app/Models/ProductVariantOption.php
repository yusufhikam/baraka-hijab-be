<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariantOption extends Model
{
    protected $fillable = [
        'product_variant_id',
        'size',
        'stock',
        'is_ready'
    ];


    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function carts():HasMany{
        return $this->hasMany(Cart::class);
    }

    public function transactionItems():HasMany{
        return $this->hasMany(TransactionItem::class);
    }
}