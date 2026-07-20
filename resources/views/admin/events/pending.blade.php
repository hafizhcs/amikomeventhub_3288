@extends('layouts.admin')

@section('title', 'Review Event Organizer')
@section('page_title', 'Review Event dari Organizer')

@section('content')

@if (session('success'))
    <div class="mb-6 p-4 rounded-xl bg-green-50 text-green-700 text-sm font-semibold">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500 text-left">
            <tr>
                <th class="px-6 py-3">Judul</th>
                <th class="px-6 py-3">Organisasi</th>
                <th class="px-6 py-3">Tanggal</th>
                <th class="px-6 py-3">Harga</th>
                <th class="px-6 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($events as $event)
                <tr class="border-t border-slate-100">
                    <td class="px-6 py-3 font-semibold">{{ $event->title }}</td>
                    <td class="px-6 py-3">{{ $event->organization->name ?? '-' }}</td>
                    <td class="px-6 py-3">{{ $event->date->format('d M Y') }}</td>
                    <td class="px-6 py-3">Rp {{ number_format($event->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-3 space-x-2">
                        <form method="POST" action="{{ route('admin.events.approve', $event) }}" class="inline">
                            @csrf
                            <button class="text-green-600 font-bold hover:underline">Setujui</button>
                        </form>
                        <button type="button" onclick="document.getElementById('reject-{{ $event->id }}').classList.toggle('hidden')"
                            class="text-red-600 font-bold hover:underline">Tolak</button>
                    </td>
                </tr>
                <tr id="reject-{{ $event->id }}" class="hidden bg-red-50/50">
                    <td colspan="5" class="px-6 py-4">
                        <form method="POST" action="{{ route('admin.events.reject', $event) }}" class="flex gap-3">
                            @csrf
                            <input type="text" name="rejection_reason" required placeholder="Alasan penolakan..."
                                class="flex-1 border border-slate-200 rounded-xl px-4 py-2 text-sm">
                            <button class="px-4 py-2 bg-red-600 text-white rounded-xl font-bold text-sm">Kirim Penolakan</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">Tidak ada event yang menunggu review.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $events->links() }}</div>
@endsection
