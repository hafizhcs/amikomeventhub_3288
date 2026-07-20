@extends('layouts.admin')

@section('title', 'Kelola Dynamic Pricing')
@section('page_title', 'Kelola Dynamic Pricing')

@section('content')
    <div class="flex justify-between items-center mb-6">
        {{-- Search --}}
        <div class="mb-4">
            <form action="{{ route('admin.ticket-prices.index') }}" method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari event atau kategori..."
                    class="px-4 py-2 border rounded-lg w-64 focus:ring focus:ring-indigo-300">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Search
                </button>
            </form>
        </div>

        {{-- Tambah Harga --}}
        <a href="{{ route('admin.ticket-prices.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg">+ Tambah
            Harga</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-xs font-bold">
                <tr>
                    <th class="px-6 py-3">Event</th>
                    <th class="px-6 py-3">Kategori</th>
                    <th class="px-6 py-3 text-center">Harga</th>
                    <th class="px-6 py-3 text-center">Mulai</th>
                    <th class="px-6 py-3 text-center">Berakhir</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prices as $price)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-3">{{ $price->event->title }}</td>
                        <td class="px-6 py-3">
                            @php
                                $color = match ($price->category) {
                                    'Early Bird' => 'bg-green-100 text-green-700',
                                    'Presale 1' => 'bg-yellow-100 text-yellow-700',
                                    'Presale 2' => 'bg-orange-100 text-orange-700',
                                    default => 'bg-blue-100 text-blue-700',
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-sm font-semibold whitespace-nowrap {{ $color }}">
                                {{ $price->category }}
                            </span>

                        </td>
                        <td class="px-6 py-3 text-center">
                            Rp {{ number_format($price->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3 text-center">
                            {{ \Carbon\Carbon::parse($price->start_date)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-3 text-center">
                            {{ \Carbon\Carbon::parse($price->end_date)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-3 flex justify-center gap-2">
                            <a href="{{ route('admin.ticket-prices.edit', $price->id) }}"
                                class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded">Edit</a>
                            <form action="{{ route('admin.ticket-prices.destroy', $price->id) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus harga ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-rose-50 text-rose-600 rounded">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-6 text-center text-slate-400">
                            Belum ada Dynamic Pricing.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $prices->links() }}
    </div>
@endsection