<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function transactions():HasMany{
        return $this->hasMany(Transaction::class);
    }
}