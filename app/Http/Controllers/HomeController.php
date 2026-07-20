<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Rating;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Partner; // tambahkan model Partner
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Halaman beranda dengan filter kategori + partner
     */
    public function index(Request $request)
    {
        // 1. Ambil semua kategori untuk tombol filter
        $categories = Category::all();

        // 2. Buat base query dengan Eager Loading
        $query = Event::with('category')
            ->approved()
            ->where('date', '>=', now())
            ->orderBy('date', 'asc');

        // 3. Jika ada parameter ?category= di URL, filter berdasarkan slug
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // 4. Eksekusi query event
        $events = $query
            ->withAvg('ratings as average_rating', 'score')
            ->withCount('ratings as reviews_count')
            ->get();

        // 5. Ambil semua partner beserta relasi kategori
        $partners = Partner::with('category')->latest()->get();

        $topReviews = Rating::with(['user', 'event'])
            ->where('score', 5)
            ->latest()
            ->take(6)
            ->get();

        // 6. Event yang sudah lewat tanggalnya ATAU stoknya habis, untuk section
        //    "Event Terdahulu & Habis Terjual"
        $pastEvents = Event::with('category')
            ->approved()
            ->where(function ($q) {
                $q->where('date', '<', now())
                  ->orWhere('stock', '<=', 0);
            })
            ->orderBy('date', 'desc')
            ->take(8)
            ->get();

        // Simpan kategori yang sedang aktif untuk highlight tombol
        $activeCategory = $request->category ?? '';

        // Kirim semua variabel ke view
        return view('welcome', compact('events', 'categories', 'activeCategory', 'partners', 'topReviews', 'pastEvents'));
    }

    /**
     * API untuk filter events via AJAX
     */
    public function getFilteredEvents(Request $request)
    {
        // Buat base query dengan Eager Loading
        $query = Event::with('category')
            ->approved()
            ->where('date', '>=', now())
            ->orderBy('date', 'asc');

        // Jika ada parameter ?category= di URL, filter berdasarkan slug
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Eksekusi query event
        $events = $query
            ->withAvg('ratings as average_rating', 'score')
            ->withCount('ratings as reviews_count')
            ->get();

        // Return events sebagai JSON
        return response()->json([
            'events' => $events,
            'isEmpty' => $events->isEmpty()
        ]);
    }

    public function profil()
    {
        $user = Auth::user();

        $successfulEventIds = Transaction::where('customer_email', $user->email)
            ->where('status', 'success')
            ->pluck('event_id')
            ->unique();

        $eligibleEvents = Event::whereIn('id', $successfulEventIds)
            ->where('date', '<=', now())
            ->orderBy('date', 'desc')
            ->get();

        $myReviews = Rating::with('event')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('profil', compact('eligibleEvents', 'myReviews'));
    }

   public function katalog(Request $request)
{
    $categories = Category::all();
    $activeCategory = $request->category ?? '';

    // Hanya memuat relasi category saja tanpa review
    $query = Event::with('category')->approved();

    if ($activeCategory) {
        $query->whereHas('category', function($q) use ($activeCategory) {
            $q->where('slug', $activeCategory);
        });
    }

    $events = $query->get();

    if ($request->ajax()) {
        return response()->json([
            'events' => $events,
            'activeCategory' => $activeCategory
        ]);
    }

    return view('katalog', compact('categories', 'activeCategory', 'events'));
}
    public function tentang()
    {
        return view('tentang');
    }

    public function kontak()
    {
        return view('contact');
    }
}