<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * READ - Menampilkan daftar semua event + pencarian
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
            ->latest()
            ->paginate(10);

        // agar pagination tetap membawa parameter search
        $events->appends(['search' => $search]);

        return view('admin.events.index', compact('events'));
    }

    /**
     * CREATE (Form) - Menampilkan form tambah event baru
     */
    public function create()
    {
        $categories = Category::all();

        return view('admin.events.create', compact('categories'));
    }

    /**
     * CREATE (Store) - Menyimpan event baru ke database
     */
    public function store(Request $request)
{
     // Menerapkan validasi data request dari pengguna
     $data = $request->validate([
        'category_id' => 'required|exists:categories,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'date' => 'required|date',
        'location' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|numeric|min:1',
        'poster' => 'nullable|image|max:2048' // Maksimal 2MB
    ]);

    if ($request->hasFile('poster')) {
        // Simpan ke direktori storage/app/public/posters
        $data['poster_path'] = $request->file('poster')->store('posters', 'public');
    }

     // Menyimpan data yang telah divalidasi ke dalam tabel menggunakan Model
     $data['organizer_id'] = auth()->id();
     \App\Models\Event::create($data);

     return redirect()->route('admin.events.index')->with('success', 'Data Event berhasil ditambahkan.');
}
    /**
     * UPDATE (Form) - Menampilkan form edit event
     */
    public function edit(Event $event)
    {
        $categories = Category::all();

        return view('admin.events.edit', compact('event', 'categories'));
    }

    /**
     * UPDATE (Save) - Menyimpan perubahan event ke database
     */
    public function update(Request $request, Event $event)
{
   $data = $request->validate([
        'category_id' => 'required|exists:categories,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'date' => 'required|date',
        'location' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|numeric|min:1',
        'poster' => 'nullable|image|max:2048'
    ]); 

    if ($request->hasFile('poster')) {
        // Hapus gambar lama jika sebelumnya sudah memiliki poster
        if ($event->poster_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($event->poster_path);
        }
        // Upload gambar baru
        $data['poster_path'] = $request->file('poster')->store('posters', 'public');
    }

    $event->update($data);
    return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
}

    /**
     * DELETE - Menghapus event dari database
     */
    public function destroy(Event $event)
    {
        $title = $event->title;

        if ($event->poster_path) {
            Storage::disk('public')->delete($event->poster_path);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event "' . $title . '" berhasil dihapus secara permanen!');
    }

    /**
     * SHOW - (Opsional, tidak dipakai di admin panel ini)
     */
    public function show(\App\Models\Event $event)
{
   // Mengambil daftar kategori untuk keperluan menu footer
    $categories = \App\Models\Category::all();
    
    // Me-render view dengan membawa data kategori dan data spesifik acara tersebut
    return view('event-detail', compact('categories', 'event'));
}

public function pendingReview()
{
    $events = Event::with('category')
        ->where('status', 'pending')
        ->latest()
        ->paginate(10);

    return view('admin.events.pending', compact('events'));
}

public function approve(Request $request, $id)
{
    // Logika untuk menyetujui event, misalnya:
     $event = Event::findOrFail($id);
     $event->update(['status' => 'approved']);

    return redirect()->back()->with('success', 'Event berhasil disetujui!');
}

}
