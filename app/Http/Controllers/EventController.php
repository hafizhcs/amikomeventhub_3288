<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Transaction;

class EventController extends Controller

{
    /**
     * Halaman detail event
     */
    public function showTicket($id)
{
    $transaction = Transaction::findOrFail($id);

    // Ambil email user yang sedang login
    $userEmail = auth()->user()->email;

    // Cocokkan email user dengan email di data transaksi
    if ($userEmail !== $transaction->customer_email) {
        abort(403, 'Akses ditolak. Ini bukan tiket Anda.');
    }

    $transaction->load(['event']);

    return view('e-ticket', [
        'transactions' => collect([$transaction])
    ]);
}
    /**
     * Halaman daftar event (index + search)
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $events = Event::with('category')
            ->when($search, function ($query, $search) {
                $query->where('title', 'LIKE', "%{$search}%")
                      ->orWhereHas('category', function ($q) use ($search) {
                          $q->where('name', 'LIKE', "%{$search}%");
                      });
            })
            ->paginate(10);

        return view('admin.events.index', compact('events'));
    }

    /**
     * Halaman detail event
     */
    public function show($id)
    {
        // Event yang belum/tidak lolos review superadmin tidak boleh diakses publik
        $event = Event::with(['category', 'ratings.user'])->approved()->findOrFail($id);
        return view('event-detail', compact('event'));
    }

    /**
     * Halaman checkout (DIPERBAIKI)
     */
    public function checkout($id)
    {
        $event = Event::findOrFail($id);
        return view('checkout', compact('event'));
    }

    /**
     * Halaman e-ticket setelah bayar
     */
    public function ticket()
    {
        $transactions = \App\Models\Transaction::with('event')
            ->where('customer_email', auth()->user()->email)
            ->whereIn('status', ['settlement', 'success', 'capture'])
            ->latest()
            ->get();

        return view('ticket', compact('transactions'));
    }
}
