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
        
        <!-- COLUMN 1: Form Input Rating (Takes 2 Columns) -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
            <div class="mb-8">
                <h1 class="text-3xl font-black text-slate-900 mb-2 tracking-tight">Profil Saya</h1>
                <p class="text-slate-500 font-medium text-sm leading-relaxed">Kelola testimoni dan rating acara yang sudah selesai untuk meningkatkan kepercayaan penyelenggara Anda.</p>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-slate-50/50 p-6">
                <h2 class="text-xl font-bold text-slate-800 mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c-.195-.39-.687-.39-.882 0L9.124 6.784a1 1 0 01-.753.548l-3.626.527c-.43.062-.602.589-.292.896l2.624 2.557a1 1 0 01.288.885L6.76 15.82a1 1 0 001.45 1.054L11.5 15.1l3.29 1.73a1 1 0 001.45-1.054l-.622-3.626a1 1 0 01.288-.885l2.624-2.557c.3-.307.13-.834-.292-.896l-3.625-.527a1 1 0 01-.753-.548L11.48 3.5z"></path>
                    </svg>
                    Berikan Rating Pasca-Acara
                </h2>

                @if($eligibleEvents->count())
                    <form action="{{ route('rating.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Select Event -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Event</label>
                            <select name="event_id" required class="w-full rounded-xl border border-slate-200 p-4 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-sm font-medium">
                                <option value="" disabled selected>-- Pilih event yang telah diikuti --</option>
                                @foreach($eligibleEvents as $event)
                                    <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->title }} — {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d M Y') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('event_id')
                                <p class="text-rose-600 text-xs mt-2 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Select Rating -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Rating</label>
                            <select name="rating" required class="w-full rounded-xl border border-slate-200 p-4 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-sm font-medium">
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ str_repeat('⭐', $i) }} {{ $i === 5 ? '(Sangat Puas)' : ($i === 1 ? '(Sangat Kurang)' : '') }}
                                    </option>
                                @endfor
                            </select>
                            @error('rating')
                                <p class="text-rose-600 text-xs mt-2 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Textarea Review -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Komentar / Testimoni</label>
                            <textarea name="review" rows="4" required class="w-full rounded-xl border border-slate-200 p-4 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-sm font-medium resize-none" placeholder="Ceritakan pengalamanmu secara jujur setelah menghadiri acara...">{{ old('review') }}</textarea>
                            @error('review')
                                <p class="text-rose-600 text-xs mt-2 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full md:w-auto px-6 py-3.5 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 transition-all duration-200 shadow-md hover:shadow-indigo-100 flex items-center justify-center gap-2">
                            <span>Kirim Ulasan</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.768 59.768 0 013.27 20.875L6 12zm0 0h7.5"></path>
                            </svg>
                        </button>
                    </form>
                @else
                    <div class="rounded-2xl border border-slate-100 bg-white p-6 text-slate-500 text-sm leading-relaxed text-center flex flex-col items-center py-10">
                        <span class="text-3xl mb-2">🎟️</span>
                        <p class="font-semibold text-slate-700 mb-1">Belum ada acara yang bisa dinilai</p>
                        <p class="max-w-md text-xs">Ulasan hanya dapat dibuat setelah acara yang kamu pesan telah selesai terlaksana dan status kehadiranmu terverifikasi.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- COLUMN 2: Sidebar (Takes 1 Column) -->
        <aside class="space-y-6">
            
            <!-- My Reviews Card -->
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100">
                <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.173c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.176 0l-3.38 2.455c-.784.57-1.838-.197-1.539-1.118l1.286-3.966a1 1 0 00-.364-1.118L2.05 9.393c-.783-.57-.38-1.81.588-1.81h4.173a1 1 0 00.95-.69l1.286-3.966z"/>
                    </svg>
                    Ulasan Saya
                </h2>

                @if($myReviews->count())
                    <div class="space-y-4 max-h-[400px] overflow-y-auto pr-1">
                        @foreach($myReviews as $review)
                            <div class="rounded-2xl border border-slate-100 p-4 bg-slate-50/50 hover:bg-slate-50 transition duration-200">
                                <div class="flex items-start justify-between gap-3 mb-2.5">
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-800 text-sm truncate">{{ $review->event->title ?? 'Acara tidak ditemukan' }}</p>
                                        <p class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="flex items-center gap-1 shrink-0 bg-amber-50 text-amber-600 px-2.5 py-1 rounded-lg text-xs font-bold">
                                        <span>{{ $review->rating }}</span>
                                        <span>★</span>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed break-words font-medium">
                                    {{ $review->review ?? 'Tidak ada komentar tambahan.' }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-slate-400 text-xs">
                        <span class="text-2xl mb-1 block">✍️</span>
                        Belum ada ulasan yang kamu tulis.
                    </div>
                @endif
            </div>

            <!-- Guide Card (Stepper) -->
            <div class="rounded-3xl bg-white p-6 shadow-sm border border-slate-100">
                <h2 class="text-xl font-bold text-slate-800 mb-5 pb-3 border-b border-slate-100">Sistem Penilaian</h2>
                
                <div class="relative pl-5 border-l-2 border-slate-100 space-y-5 text-xs text-slate-500 font-medium">
                    {{-- Step 1 --}}
                    <div class="relative">
                        <span class="absolute -left-[29px] top-0.5 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-indigo-500 ring-4 ring-white"></span>
                        <h4 class="font-bold text-slate-800 mb-0.5 text-sm">Hadir & Selesai</h4>
                        <p class="leading-relaxed">Pesan tiket resmi Anda dan ikuti jalannya rangkaian acara hingga selesai.</p>
                    </div>
                    
                    {{-- Step 2 --}}
                    <div class="relative">
                        <span class="absolute -left-[29px] top-0.5 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-indigo-500 ring-4 ring-white"></span>
                        <h4 class="font-bold text-slate-800 mb-0.5 text-sm">Tulis Review Jujur</h4>
                        <p class="leading-relaxed">Berikan penilaian bintang dan testimoni objektif mengenai kualitas penyelenggaraan acara.</p>
                    </div>

                    {{-- Step 3 --}}
                    <div class="relative">
                        <span class="absolute -left-[29px] top-0.5 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-indigo-500 ring-4 ring-white"></span>
                        <h4 class="font-bold text-slate-800 mb-0.5 text-sm">Bantu Komunitas</h4>
                        <p class="leading-relaxed">Ulasan Anda membantu calon peserta memilih event terbaik sekaligus mengevaluasi EO.</p>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection