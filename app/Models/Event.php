<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Category;
use App\Models\Rating;
use App\Models\Transaction;
use App\Models\User;
use App\Models\TicketPrice;
use Carbon\Carbon;


class Event extends Model
{
    use HasFactory;

    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'category_id',
        'organizer_id',
        'organization_id',
        'title',
        'description',
        'date',
        'location',
        'price',
        'stock',
        'poster_path',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    /**
     * Organisasi (HIMA/panitia) pemilik event ini. Nullable karena event
     * lama (sebelum sistem organisasi ada) belum tentu terhubung ke satu.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function getAverageRatingAttribute()
    {
        $average = $this->ratings->avg('score');

        return $average ? round($average, 1) : 0;
    }

    /**
     * Otomatis membuat URL gambar yang benar saat dipanggil di Blade
     */
    public function getPosterUrlAttribute()
    {
        if ($this->poster_path) {
            return asset('storage/' . $this->poster_path);
        }
        // Jika tidak ada gambar, arahkan ke placeholder
        return 'https://via.placeholder.com/400x250?text=No+Image';
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function ticketPrices()
    {
        return $this->hasMany(TicketPrice::class);
    }

    public function currentPrice()
    {
        $today = Carbon::now();

        $ticket = $this->ticketPrices()
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->first();

        // Kalau ada harga aktif → pakai itu, kalau tidak → fallback ke price default
        return $ticket ? $ticket->price : $this->price;
    }

    /**
     * Scope: hanya event yang sudah lolos review superadmin.
     * Dipakai di semua query yang tampil ke publik (katalog, detail, checkout).
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

}