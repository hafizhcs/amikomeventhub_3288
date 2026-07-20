<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promo;

class PromoSeeder extends Seeder
{
    public function run(): void
    {
        // Kupon Persentase (Diskon 50%)
        Promo::create([
            'code' => 'MAHASISWA50',
            'type' => 'percentage',
            'value' => 50,
            'expired_at' => now()->addDays(30),
        ]);

        // Kupon Nominal Tetap (Potongan Rp 15.000)
        Promo::create([
            'code' => 'LAUNCH2026',
            'type' => 'fixed',
            'value' => 15000,
            'expired_at' => now()->addDays(30),
        ]);
    }
}
