<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Organization;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrganizerController extends Controller
{
    /**
     * Form pendaftaran organisasi baru. Hanya untuk user yang login dan
     * belum tergabung ke organisasi manapun.
     */
    public function registerForm()
    {
        $user = auth()->user();

        if ($user->organization_id) {
            return redirect()->route('organizer.dashboard');
        }

        return view('organizer.register');
    }

    /**
     * Simpan pengajuan organisasi baru. Status awal selalu 'pending'
     * sampai disetujui Superadmin lewat panel admin.
     */
    public function registerStore(Request $request)
    {
        $user = auth()->user();

        if ($user->organization_id) {
            return redirect()->route('organizer.dashboard');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:255',
        ]);

        $slug = Str::slug($data['name']);
        $original = $slug;
        $i = 1;
        while (Organization::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $i++;
        }
        $data['slug'] = $slug;
        $data['status'] = Organization::STATUS_PENDING;
        $data['owner_id'] = $user->id;

        $organization = Organization::create($data);

        // Langsung jadikan pendaftar sebagai pengurus organisasi tsb,
        // supaya dia bisa lihat status pengajuannya dari dashboard.
        // Event/tools organizer tetap terkunci sampai statusnya 'approved'
        // (lihat OrganizerMiddleware & pengecekan di dashboard()/events).
        $user->update([
            'role' => 'organizer',
            'organization_id' => $organization->id,
        ]);

        return redirect()->route('organizer.dashboard')
            ->with('success', 'Pengajuan organisasi berhasil dikirim. Menunggu verifikasi Superadmin.');
    }

    /**
     * Dashboard utama organizer: status organisasi, ringkasan pendapatan,
     * dan daftar event miliknya.
     */
   public function dashboard()
{
    $organization = auth()->user()->organization;

    $events = Event::where('organization_id', $organization->id)->latest()->get();

    $totalRevenue = Transaction::whereIn('event_id', $events->pluck('id'))
        ->whereIn('status', ['settlement', 'success'])
        ->sum('total_price');

    $ticketsSold = Transaction::whereIn('event_id', $events->pluck('id'))
        ->whereIn('status', ['settlement', 'success'])
        ->count();

    $pendingReview = $events->where('status', Event::STATUS_PENDING)->count();

    // --- LOGIKA DATA GRAFIK PERTUMBUHAN EVENT ---
    $eventsPerMonth = Event::where('organization_id', $organization->id)
        ->selectRaw('MONTH(date) as month, COUNT(*) as total')
        ->whereYear('date', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month');

    $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
    $chartData = [];
    
    for ($i = 1; $i <= 12; $i++) {
        $chartData[] = $eventsPerMonth[$i] ?? 0;
    }

    return view('organizer.dashboard', compact(
        'organization',
        'events',
        'totalRevenue',
        'ticketsSold',
        'pendingReview',
        'chartLabels',
        'chartData'
    ));
}

    public function eventsIndex()
    {
        $organization = auth()->user()->organization;

        $events = Event::with('category')
            ->where('organization_id', $organization->id)
            ->latest()
            ->paginate(10);

        return view('organizer.events.index', compact('events', 'organization'));
    }

    public function eventsCreate()
    {
        $organization = auth()->user()->organization;
        $categories = Category::all();

        return view('organizer.events.create', compact('categories', 'organization'));
    }

    public function eventsStore(Request $request)
    {
        $user = auth()->user();
        $organization = $user->organization;

        if (! $organization->isApproved()) {
            return back()->with('error', 'Organisasi Anda belum disetujui Superadmin, belum bisa membuat event.');
        }

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:1',
            'poster' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $data['organizer_id'] = $user->id;
        $data['organization_id'] = $organization->id;
        // Setiap event baru dari organizer WAJIB direview Superadmin dulu
        // sebelum tayang di katalog publik.
        $data['status'] = Event::STATUS_PENDING;

        Event::create($data);

        return redirect()->route('organizer.events.index')
            ->with('success', 'Event berhasil diajukan dan menunggu review Superadmin.');
    }

    public function eventsEdit(Event $event)
    {
        $this->authorizeOwnership($event);

        $categories = Category::all();

        return view('organizer.events.edit', compact('event', 'categories'));
    }

    public function eventsUpdate(Request $request, Event $event)
    {
        $this->authorizeOwnership($event);

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:1',
            'poster' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('poster')) {
            if ($event->poster_path) {
                Storage::disk('public')->delete($event->poster_path);
            }
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $event->update($data);

        return redirect()->route('organizer.events.index')->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Pastikan organizer hanya bisa mengelola event milik organisasinya sendiri.
     */
    private function authorizeOwnership(Event $event): void
    {
        if ($event->organization_id !== auth()->user()->organization_id) {
            abort(403, 'Anda tidak berhak mengelola event ini.');
        }
    }

public function show($id)
    {
        // Cek terlebih dahulu apakah ID tersebut terdaftar sebagai Organization (Organizer)
        $organizer = Organization::with(['events.ratings.user', 'events.ratings.event'])->find($id);

        if ($organizer) {
            // Alur untuk Organizer (berdasarkan Organisasi)
            $events = $organizer->events()->latest()->get();
            $reviews = $organizer->events->flatMap->ratings;
        } else {
            // Alur jika Super Admin / User (ambil langsung dari tabel Event berdasarkan organizer_id)
            $organizer = User::findOrFail($id);
            
            $events = Event::with(['ratings.user', 'ratings.event'])
                        ->where('organizer_id', $id)
                        ->latest()
                        ->get();
                        
            $reviews = $events->flatMap->ratings;
        }

        // Sesuaikan path view dengan lokasi file blade Anda (misal: 'admin.organizer.show' atau 'organizer.show')
        return view('organizer.show', compact('organizer', 'events', 'reviews'));
    }



}
