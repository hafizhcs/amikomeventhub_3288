<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');

        $organizations = Organization::with('owner')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10);

        return view('admin.organizations.index', compact('organizations', 'status'));
    }

    public function approve(Organization $organization)
    {
        $organization->update(['status' => Organization::STATUS_APPROVED]);

        return back()->with('success', 'Organisasi "' . $organization->name . '" disetujui.');
    }

    public function suspend(Organization $organization)
    {
        $organization->update(['status' => Organization::STATUS_SUSPENDED]);

        return back()->with('success', 'Organisasi "' . $organization->name . '" dibekukan.');
    }

public function show($slug)
{
    // Cari organizer berdasarkan slug
    $organizer = Organizer::where('slug', $slug)->firstOrFail();

    // Ambil daftar event yang dibuat oleh organizer ini,
    // Sekaligus hitung jumlah komentar, jumlah rating, dan rata-rata ratingnya
    $events = Event::where('organizer_id', $organizer->id)
        ->withCount(['comments', 'ratings']) // Menghasilkan comments_count & ratings_count
        ->withAvg('ratings', 'score')        // Menghasilkan ratings_avg_score (asumsi nama field nilai rating adalah 'score')
        ->latest()
        ->paginate(6); // Menampilkan misal 6 event per halaman

    return view('organizer.show', compact('organizer', 'events'));
}
}
