<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingOrigin extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'address',
        'province_id',
        'province_name',
        'city_id',
        'city_name',
        'district_id',
        'district_name',
        'subdistrict_id',
        'subdistrict_name',
        'postal_code',
        'is_active',
        'is_default'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean'
    ];

    protected static function booted()
    {
        static::created(function ($shippingOrigin) {
            if ($shippingOrigin->is_default) {
                $shippingOrigin::where('id', '!=', $shippingOrigin->id)
                                ->where('is_default', true)
                                ->update(['is_default' => false]);
            }
        });
    }
}