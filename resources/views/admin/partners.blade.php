@extends('layouts.app')

@section('title', 'Partner Kami - AmikomEventHub')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="flex justify-between items-end mb-10">
        <div>
            <h2 class="text-3xl font-extrabold mb-2">Partner Kami</h2>
            <p class="text-slate-500 font-medium">
                Platform AmikomEventHub didukung oleh berbagai mitra dari berbagai kategori.
            </p>
        </div>
    </div>

    @if($partners->isEmpty())
        <div class="text-center py-20">
            <div class="text-6xl mb-4">🤝</div>
            <h3 class="text-2xl font-bold text-slate-700 mb-2">Belum ada partner terdaftar</h3>
            <p class="text-slate-500">Partner akan segera ditambahkan.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($partners as $partner)
                <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
                    {{-- Logo Partner --}}
                    @if($partner->logo)
                        <img src="{{ asset('storage/' . $partner->logo) }}" 
                             alt="{{ $partner->name }}" 
                             class="w-full h-32 object-contain mb-4">
                    @else
                        <div class="w-full h-32 bg-slate-100 flex items-center justify-center mb-4">
                            <span class="text-4xl">🏢</span>
                        </div>
                    @endif

                    {{-- Nama Partner --}}
                    <h3 class="text-lg font-bold mb-1">{{ $partner->name }}</h3>

                    {{-- Kategori Partner --}}
                    <p class="text-sm text-slate-500">
                        {{ $partner->category->name ?? 'Tanpa Kategori' }}
                    </p>
                </div>
            @endforeach
        </div>
    @endif
</section>
@endsection
