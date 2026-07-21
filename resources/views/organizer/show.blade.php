@extends('layouts.app')

@section('title', ($organizer->name ?? 'Detail') . ' - Profil Penyelenggara')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-12 space-y-12 animate-fade-in">
    
    <!-- Profil Header -->
    <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="w-20 h-20 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-black text-3xl shadow-md shadow-indigo-100">
                {{ substr($organizer->name ?? 'A', 0, 1) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl md:text-3xl font-black text-slate-900">{{ $organizer->name }}</h1>
                    <span class="bg-indigo-50 text-indigo-600 px-2.5 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                        ✓ Verified Organizer
                    </span>
                </div>
                <p class="text-slate-500 text-sm mt-1">Penyelenggara event resmi di platform AmikomEventHub</p>
            </div>
        </div>
        <div class="flex gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100 w-full md:w-auto justify-around">
            <div class="text-center px-4">
                <p class="text-xs text-slate-400 font-bold uppercase">Total Event</p>
                <p class="text-xl font-black text-slate-800 mt-0.5">{{ $events->count() }}</p>
            </div>
            <div class="border-r border-slate-200"></div>
            <div class="text-center px-4">
                <p class="text-xs text-slate-400 font-bold uppercase">Total Ulasan</p>
                <p class="text-xl font-black text-slate-800 mt-0.5">{{ $reviews->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Grid Layout: Daftar Event & Daftar Ulasan -->
    <div class="grid gap-10 lg:grid-cols-2 items-start">
        
        <!-- Kolom 1: Event yang Telah Dibuat -->
        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm space-y-6">
            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                📅 Event oleh Penyelenggara Ini
            </h2>

            @if($events->count())
                <div class="space-y-4">
                    @foreach($events as $ev)
                        <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition flex items-center justify-between gap-4">
                            <div>
                                <h3 class="font-bold text-slate-800 text-sm">{{ $ev->title }}</h3>
                                <p class="text-xs text-slate-500 mt-1">🗓️ {{ $ev->date ? \Carbon\Carbon::parse($ev->date)->translatedFormat('d M Y') : 'Jadwal belum ditentukan' }}</p>
                            </div>
                            <a href="{{ route('events.show', $ev->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition shrink-0">
                                Detail Event
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-400 text-center py-6">Belum ada event yang dipublikasikan.</p>
            @endif
        </div>

        <!-- Kolom 2: Detail Ulasan Teks dari Pelanggan -->
        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm space-y-6">
            <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                💬 Ulasan & Testimoni Pelanggan
            </h2>

            @if($reviews->count())
                <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                    @foreach($reviews as $rev)
                        <div class="p-5 rounded-2xl border border-slate-100 bg-slate-50/50 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-bold text-slate-800 text-sm">{{ $rev->user->name ?? 'Pelanggan' }}</p>
                                    <p class="text-[11px] text-indigo-600 font-medium">Acara: {{ $rev->event->title ?? '-' }}</p>
                                </div>
                                <div class="flex items-center gap-1 bg-amber-50 text-amber-600 px-2.5 py-1 rounded-lg text-xs font-bold">
                                    <span>{{ $rev->rating ?? $rev->score ?? 0 }}</span>
                                    <span>★</span>
                                </div>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed bg-white p-3.5 rounded-xl border border-slate-100 font-medium">
                                "{{ $rev->review ?? $rev->comment ?? 'Tidak ada komentar teks.' }}"
                            </p>
                            <p class="text-[10px] text-slate-400 text-right">{{ $rev->created_at ? $rev->created_at->diffForHumans() : '' }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 text-slate-400 text-xs">
                    <span class="text-3xl block mb-2">⭐</span>
                    Belum ada ulasan teks untuk event dari penyelenggara ini.
                </div>
            @endif
        </div>

    </div>
</div>
@endsection