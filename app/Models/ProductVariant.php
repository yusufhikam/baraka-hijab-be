<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    protected $fillable = [
        'name',
        'color',
        'weight',
        'product_id',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }


    public function productVariantOptions():HasMany{
        return $this->hasMany(ProductVariantOption::class);
    }

    public function photos():HasMany{
        return $this->hasMany(Photo::class);
    }

  

    // public function transactionItems():HasMany{
    //     return $this->hasMany(TransactionItem::class);
    // }
}