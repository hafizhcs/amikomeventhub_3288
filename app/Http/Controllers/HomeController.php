<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\Partner; // tambahkan model Partner

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
            ->where('date', '>=', now())
            ->orderBy('date', 'asc');

        // 3. Jika ada parameter ?category= di URL, filter berdasarkan slug
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // 4. Eksekusi query event
        $events = $query->get();

        // 5. Ambil semua partner beserta relasi kategori
        $partners = Partner::with('category')->latest()->get();

        // Simpan kategori yang sedang aktif untuk highlight tombol
        $activeCategory = $request->category ?? '';

        // Kirim semua variabel ke view
        return view('welcome', compact('events', 'categories', 'activeCategory', 'partners'));
    }

    public function profil()
    {
        return view('profil');
    }

    public function katalog()
    {
        return view('katalog');
    }

    public function bantuan()
    {
        return view('bantuan');
    }

    public function kontak()
    {
        return view('contact');
    }
}
