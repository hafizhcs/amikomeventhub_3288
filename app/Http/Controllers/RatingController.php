<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Rating;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $event = Event::findOrFail($request->event_id);

        if (now()->lt($event->date)) {
            return back()->with('error', 'Ulasan hanya dapat diberikan setelah acara selesai.');
        }

        $hasPurchased = Transaction::where('event_id', $event->id)
            ->where('customer_email', Auth::user()->email)
            ->where('status', 'success')
            ->exists();

        if (! $hasPurchased) {
            return back()->with('error', 'Hanya peserta yang berhasil membeli tiket yang dapat memberi ulasan.');
        }

        Rating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'event_id' => $event->id,
            ],
            [
                'organizer_id' => $event->organizer_id,
                'score' => $request->rating,
                'review' => $request->review,
            ]
        );

        return back()->with('success', 'Review berhasil disimpan.');
    }
}
