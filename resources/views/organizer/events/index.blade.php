@extends('layouts.organizer')

@section('title', 'Event Saya')
@section('page_title', 'Event Saya')

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-slate-500">Kelola event milik {{ $organization->name }}.</p>
    @if ($organization->isApproved())
        <a href="{{ route('organizer.events.create') }}" class="px-5 py-2 bg-emerald-600 text-white rounded-xl font-bold text-sm hover:bg-emerald-700">
            + Buat Event
        </a>
    @endif
</div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500 text-left">
            <tr>
                <th class="px-6 py-3">Judul</th>
                <th class="px-6 py-3">Tanggal</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Stok</th>
                <th class="px-6 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($events as $event)
                <tr class="border-t border-slate-100">
                    <td class="px-6 py-3 font-semibold">{{ $event->title }}</td>
                    <td class="px-6 py-3">{{ $event->date->format('d M Y') }}</td>
                    <td class="px-6 py-3">
                        @if ($event->status === 'approved')
                            <span class="px-2 py-1 rounded-full bg-green-50 text-green-600 text-xs font-bold">Tayang</span>
                        @elseif ($event->status === 'pending')
                            <span class="px-2 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-bold">Menunggu Review</span>
                        @else
                            <span class="px-2 py-1 rounded-full bg-red-50 text-red-600 text-xs font-bold" title="{{ $event->rejection_reason }}">Ditolak</span>
                        @endif
                    </td>
                    <td class="px-6 py-3">{{ $event->stock }}</td>
                    <td class="px-6 py-3">
                        <a href="{{ route('organizer.events.edit', $event) }}" class="text-indigo-600 font-bold hover:underline">Edit</a>
                    </td>
                </tr>
                @if ($event->status === 'rejected' && $event->rejection_reason)
                    <tr class="bg-red-50/50">
                        <td colspan="5" class="px-6 py-2 text-xs text-red-500">Alasan ditolak: {{ $event->rejection_reason }}</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada event.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $events->links() }}</div>
@endsection
