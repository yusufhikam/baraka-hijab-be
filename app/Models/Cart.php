<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'product_variant_option_id',
        'quantity',
    ];

    public function productVariantOption(): BelongsTo
    {
        return $this->belongsTo(ProductVariantOption::class);
    }

    public function getProductVariantAttribute(){
        return $this->productVariantOption()?->productVariant();
    }

    public function getProductAttribute(){
        return $this->productVariantOption()?->productVariant()?->product();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}