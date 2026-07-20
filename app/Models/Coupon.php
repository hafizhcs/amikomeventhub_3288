<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit
    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'type',
        'value',
        'expired_at',
    ];

    /**
     * Memeriksa apakah kupon masih aktif dan belum kedaluwarsa.
     */
    public function isValid()
    {
        if (is_null($this->expired_at)) {
            return true;
        }

        return \Carbon\Carbon::parse($this->expired_at)->isToday() || \Carbon\Carbon::parse($this->expired_at)->isFuture();
    }
}