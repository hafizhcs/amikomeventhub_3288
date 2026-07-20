<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Event;
use App\Models\Transaction;


class Organization extends Model
{
    use HasFactory;

    public const STATUS_PENDING   = 'pending';
    public const STATUS_APPROVED  = 'approved';
    public const STATUS_SUSPENDED = 'suspended';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo_path',
        'contact_email',
        'contact_phone',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'status',
        'owner_id',
    ];

    /**
     * User yang mendaftarkan / bertanggung jawab atas organisasi ini.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Semua user (pengurus) yang tergabung dalam organisasi ini.
     */
    public function members()
    {
        return $this->hasMany(User::class, 'organization_id');
    }

    /**
     * Semua event yang diselenggarakan oleh organisasi ini.
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'organization_id');
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isSuspended(): bool
    {
        return $this->status === self::STATUS_SUSPENDED;
    }

    /**
     * Total pendapatan (transaksi sukses/lunas) dari seluruh event organisasi ini.
     * Dipakai nanti di dashboard analitik organizer.
     */
    public function getTotalRevenueAttribute()
    {
        return Transaction::whereIn('event_id', $this->events()->pluck('id'))
            ->whereIn('status', ['settlement', 'success'])
            ->sum('total_price');
    }
}
