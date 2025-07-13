<?php

namespace App\Models;

use App\Services\WilayahService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'provinsi',
        'provinsi_name',
        'kabupaten',
        'kabupaten_name',
        'kecamatan',
        'kecamatan_name',
        'kelurahan',
        'kelurahan_name',
        'postal_code',
        'detail',
        'is_primary'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions():HasMany{
        return $this->hasMany(Transaction::class);
    }
}