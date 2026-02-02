<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'province_id',
        'province_name',
        'city_id',
        'city_name',
        'district_id',
        'district_name',
        'subdistrict_id',
        'subdistrict_name',
        'postal_code',
        'recipient_name',
        'phone_number',
        'mark_as',
        'detail',
        'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean'
    ];

    protected $appends = [
        'label' 
    ];

    public function label(): Attribute{
        return Attribute::make(
            get: fn($value, array $attributes) => 
                    collect([
                    $attributes['subdistrict_name'] ?? null,
                    'KEC.' . $attributes['district_name'] ?? null,
                    'KAB.' .$attributes['city_name'] ?? null,
                    $attributes['province_name'] ?? null,
                    'ID '.$attributes['postal_code'] ?? null,
                ])
                ->filter()
                ->implode(', ')
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions():HasMany{
        return $this->hasMany(Transaction::class);
    }
}