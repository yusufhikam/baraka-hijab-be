<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentLog extends Model
{
    protected $fillable = [
        'transaction_id',
        'raw_response',
        'status',
    ];

    public function transaction():BelongsTo{
        return $this->belongsTo(Transaction::class);
    }
}
