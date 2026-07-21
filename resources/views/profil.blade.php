@extends('layouts.app')

@section('title', 'Profil Saya - AmikomEventHub')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-12 space-y-10 animate-fade-in">
    
    <!-- ================= ALERTS SECTION ================= -->
    @if(session('success'))
        <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-5 text-emerald-800 flex items-start gap-4 shadow-sm">
            <svg class="w-6 h-6 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h4 class="font-bold text-emerald-900">Berhasil!</h4>
                <p class="text-sm text-emerald-700/90 mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl bg-rose-50 border border-rose-100 p-5 text-rose-800 flex items-start gap-4 shadow-sm">
            <svg class="w-6 h-6 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <h4 class="font-bold text-rose-900">Gagal!</h4>
                <p class="text-sm text-rose-700/90 mt-0.5">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- ================= MAIN LAYOUT ================= -->
    <div class="grid gap-10 lg:grid-cols-3 items-start">
        
        <!-- COLUMN 1: List Ulasan Pelanggan / Peserta (Takes 2 Columns) -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-black text-slate-900 mb-2 tracking-tight">Profil & Ulasan Acara</h1>
                <p class="text-slate-500 font-medium text-sm leading-relaxed">Menampilkan ulasan dan testimoni murni dari pelanggan atau peserta yang telah mengikuti acara yang telah selesai.</p>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-6">
                <h2 class="text-xl font-bold text-slate-800 mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c-.195-.39-.687-.39-.882 0L9.124 6.784a1 1 0 01-.753.548l-3.626.527c-.43.062-.602.589-.292.896l2.624 2.557a1 1 0 01.288.885L6.76 15.82a1 1 0 001.45 1.054L11.5 15.1l3.29 1.73a1 1 0 001.45-1.054l-.622-3.626a1 1 0 01.288-.885l2.624-2.557c.3-.307.13-.834-.292-.896l-3.625-.527a1 1 0 01-.753-.548L11.48 3.5z"></path>
                    </svg>
                    Daftar Ulasan Peserta
                </h2>

                {{-- Ganti variabel $eligibleEvents dengan variabel penampung ulasan masuk, misal: $receivedReviews --}}
                @if(isset($receivedReviews) && $receivedReviews->count())
                    <div class="space-y-4">
                        @foreach($receivedReviews as $review)
                            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs transition duration-200">
                                <div class="flex items-start justify-between gap-4 mb-3">
                                    <div>
                                        <h3 class="font-bold text-slate-800 text-base">{{ $review->event->title ?? 'Acara' }}</h3>
                                        <p class="text-xs text-slate-500 mt-0.5">Diberikan oleh: <span class="font-semibold text-slate-700">{{ $review->user->name ?? 'Peserta' }}</span> • <span class="text-slate-400">{{ $review->created_at->diffForHumans() }}</span></p>
                                    </div>
                                    <div class="flex items-center gap-1 shrink-0 bg-amber-50 text-amber-600 px-3 py-1.5 rounded-xl text-sm font-bold">
                                        <span>{{ $review->rating }}</span>
                                        <span>★</span>
                                    </div>
                                </div>
                                <p class="text-sm text-slate-600 leading-relaxed font-medium bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    "{{ $review->review ?? 'Tidak ada komentar tertulis.' }}"
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border border-slate-100 bg-white p-6 text-slate-500 text-sm leading-relaxed text-center flex flex-col items-center py-12">
                        <span class="text-4xl mb-3">💬</span>
                        <p class="font-bold text-slate-700 mb-1">Belum ada ulasan dari peserta</p>
                        <p class="max-w-md text-xs text-slate-400">Ulasan dari pelanggan atau peserta akan otomatis muncul di sini setelah acara selesai diselenggarakan.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- COLUMN 2: Sidebar (Takes 1 Column) -->
        <aside class="space-y-6">
            
            <!-- Ringkasan Informasi / Statistik Singkat -->
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100">
                <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.173c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.176 0l-3.38 2.455c-.784.57-1.838-.197-1.539-1.118l1.286-3.966a1 1 0 00-.364-1.118L2.05 9.393c-.783-.57-.38-1.81.588-1.81h4.173a1 1 0 00.95-.69l1.286-3.966z"/>
                    </svg>
                    Informasi Profil
                </h2>
                <p class="text-xs text-slate-500 leading-relaxed font-medium">
                    Halaman ini dirancang transparan untuk menampilkan testimoni asli dari peserta guna menjaga kredibilitas dan kualitas acara yang Anda kelola.
                </p>
            </div>

            <!-- Guide Card (Informasi Sistem) -->
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100">
                <h2 class="text-xl font-bold text-slate-800 mb-5 pb-3 border-b border-slate-100">Ketentuan Ulasan</h2>
                
                <div class="relative pl-5 border-l-2 border-slate-100 space-y-5 text-xs text-slate-500 font-medium">
                    {{-- Point 1 --}}
                    <div class="relative">
                        <span class="absolute -left-[29px] top-0.5 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-indigo-500 ring-4 ring-white"></span>
                        <h4 class="font-bold text-slate-800 mb-0.5 text-sm">Murni Dari Peserta</h4>
                        <p class="leading-relaxed">Ulasan hanya dapat dikirimkan oleh peserta yang telah memesan dan terverifikasi mengikuti acara.</p>
                    </div>
                    
                    {{-- Point 2 --}}
                    <div class="relative">
                        <span class="absolute -left-[29px] top-0.5 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-indigo-500 ring-4 ring-white"></span>
                        <h4 class="font-bold text-slate-800 mb-0.5 text-sm">Acara Selesai</h4>
                        <p class="leading-relaxed">Kolom ulasan baru akan terbuka bagi peserta setelah jadwal pelaksanaan acara dinyatakan selesai.</p>
                    </div>

                    {{-- Point 3 --}}
                    <div class="relative">
                        <span class="absolute -left-[29px] top-0.5 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-indigo-500 ring-4 ring-white"></span>
                        <h4 class="font-bold text-slate-800 mb-0.5 text-sm">Transparansi</h4>
                        <p class="leading-relaxed">Penyelenggara tidak dapat mengubah atau menghapus ulasan objektif yang diberikan oleh peserta.</p>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection