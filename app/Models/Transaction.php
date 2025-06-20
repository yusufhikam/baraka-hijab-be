<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'address_id',
        'status',
        'total_price',
        'snap_token',
        'snap_url',
        'payment_type',
        'payment_status',
        'payment_code',
        'expired_at',
        'paid_at'
    ];

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function address():BelongsTo{
        return $this->belongsTo(Address::class);
    }

    public function transactionItems():HasMany{
        return $this->hasMany(TransactionItem::class);
    }

    public function paymentLogs():HasMany{
        return $this->hasMany(PaymentLog::class);
    }
}
