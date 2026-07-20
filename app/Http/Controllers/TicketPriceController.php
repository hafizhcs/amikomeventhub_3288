<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketPrice;
use Illuminate\Http\Request;

class TicketPriceController extends Controller
{
    public function index(Request $request)
{
    $query = TicketPrice::with('event')->latest();

    // Search filter
    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('event', fn($q) => $q->where('title','like',"%{$search}%"))
              ->orWhere('category','like',"%{$search}%");
    }

    // Gunakan paginate, bukan get
    $prices = $query->paginate(10); // 10 item per halaman

    return view('admin.ticket_prices.index', compact('prices'));
}


    public function create()
    {
        $events = Event::orderBy('title')->get();
        return view('admin.ticket_prices.create', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id'   => 'required|exists:events,id',
            'category'   => 'required|string|max:100',
            'price'      => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        TicketPrice::create($request->all());

        return redirect()->route('admin.ticket-prices.index')
                         ->with('success', 'Harga tiket berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $ticketPrice = TicketPrice::findOrFail($id);
        $events = Event::orderBy('title')->get();
        return view('admin.ticket_prices.edit', compact('ticketPrice','events'));
    }

    public function update(Request $request, $id)
    {
        $ticketPrice = TicketPrice::findOrFail($id);

        $ticketPrice->update($request->validate([
            'event_id'   => 'required|exists:events,id',
            'category'   => 'required|string|max:100',
            'price'      => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]));

        return redirect()->route('admin.ticket-prices.index')
                         ->with('success','Dynamic pricing berhasil diupdate.');
    }

    public function destroy($id)
{
    $ticketPrice = TicketPrice::find($id);

    if (!$ticketPrice) {
        return redirect()
            ->route('admin.ticket-prices.index')
            ->with('error', 'Data harga tiket tidak ditemukan.');
    }

    $ticketPrice->delete();

    return redirect()
        ->route('admin.ticket-prices.index')
        ->with('success', 'Voucher berhasil dihapus.');
}
}
