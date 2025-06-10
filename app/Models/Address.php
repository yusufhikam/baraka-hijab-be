<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'kelurahan',
        'postal_code',
        'detail',
        'is_primary'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}