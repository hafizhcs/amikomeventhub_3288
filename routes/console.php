<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Http\Controllers\CheckoutController;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 1. Definisikan command Artisan untuk melepaskan tiket kedaluwarsa
Artisan::command('tickets:release-expired', function () {
    app(CheckoutController::class)->releaseExpiredReservations();
    $this->info('Tiket expired berhasil dibatalkan dan stok dikembalikan.');
})->purpose('Melepaskan reservasi tiket yang kedaluwarsa dan mengembalikan stok');

// 2. Daftarkan jadwal agar berjalan otomatis setiap 1 menit
Schedule::command('tickets:release-expired')->everyMinute();