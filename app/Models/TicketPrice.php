<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketPrice extends Model
{
    protected $fillable = [
        'event_id',
        'category',
        'price',
        'start_date',
        'end_date',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}