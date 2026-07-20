<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;

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

        return view('admin.dashboard', compact(
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'pendingOrders',
            'recentTransactions'
        ));
    }
}
