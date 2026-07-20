<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'organizer_id',
        'score',
        'review'
    ];

    protected $casts = [
        'score' => 'integer',
    ];

    protected $appends = ['rating'];

    public function getRatingAttribute()
    {
        return $this->score;
    }

    public function setRatingAttribute($value)
    {
        $this->attributes['score'] = $value;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }
}
