<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\User; // Pastikan untuk mengimpor model User

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin
     */
    public function index()
    {
        // 1. Menjumlahkan semua nominal total_price dari transaksi sukses/lunas
        $totalRevenue = Transaction::whereIn('status', ['settlement', 'success'])
            ->sum('total_price');

        // 2. Menghitung jumlah tiket yang sudah lunas
        $ticketsSold = Transaction::whereIn('status', ['settlement', 'success'])
            ->count();

        // 3. Menghitung jumlah acara mendatang yang aktif
        $activeEvents = Event::where('date', '>=', now())
            ->count();

        // 4. Menghitung jumlah pesanan pending
        $pendingOrders = Transaction::where('status', 'pending')
            ->count();

        // 5. Menyertakan 5 transaksi terbaru
        $recentTransactions = Transaction::with('event')
            ->latest()
            ->take(5)
            ->get();

        // 6. Mengambil data analitik grafik untuk 6 bulan terakhir
        $chartLabels = [];
        $chartUserData = [];
        $chartEventData = [];

        // Loop mundur dari 5 bulan lalu hingga bulan ini (total 6 bulan)
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);

            // Mendapatkan nama bulan singkat otomatis sesuai locale aplikasi (misal: Jan, Feb, Mei)
            $chartLabels[] = $date->translatedFormat('M');

            // Hitung user baru yang mendaftar pada bulan & tahun spesifik tersebut
            $chartUserData[] = User::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

            // Hitung event baru yang dibuat pada bulan & tahun spesifik tersebut
            $chartEventData[] = Event::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
        }

        return view('admin.dashboard', compact(
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'pendingOrders',
            'recentTransactions',
            'chartLabels',     // Dikirim ke view Chart
            'chartUserData',   // Dikirim ke view Chart
            'chartEventData'   // Dikirim ke view Chart
        ));
    }
}