<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'event_id','order_id','customer_name','customer_email',
        'customer_phone','total_price','status','snap_token','expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function event() {
        return $this->belongsTo(Event::class);
    }
}
