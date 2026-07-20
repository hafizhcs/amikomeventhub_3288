@extends('layouts.app')

@section('title', $event->title . ' - AmikomEventHub')

@section('content')
    <main class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-12 animate-fade-in">

        <!-- ================= LEFT COLUMN: STICKY POSTER & ORGANIZER ================= -->
        <div class="lg:col-span-1">
            <div class="sticky top-28 space-y-6">
                {{-- Poster Card --}}
                <div
                    class="group relative overflow-hidden rounded-3xl bg-slate-100 shadow-xl border border-slate-100/80 transition-all duration-300 hover:shadow-2xl">
                    @if($event->poster_path)
                                <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                        ? asset('storage/' . $event->poster_path)
                        : 'https://placehold.co/600x800' }}" alt="{{ $event->title }}"
                                    class="w-full object-cover aspect-[3/4] transition-transform duration-500 group-hover:scale-105">
                    @else
                        <div
                            class="w-full aspect-[3/4] bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 flex flex-col items-center justify-center text-7xl select-none">
                            <span>🎪</span>
                            <span class="text-xs font-bold text-slate-400 mt-4 tracking-wider uppercase">No Poster
                                Available</span>
                        </div>
                    @endif
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-slate-900/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                    </div>
                </div>

                {{-- Penyedia Card --}}
                <div
                    class="p-6 bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200">
                    <h4 class="font-bold text-slate-400 text-[10px] uppercase tracking-widest mb-4">Disediakan Oleh</h4>

                    <div class="flex items-center gap-4">
                        {{-- Organizer Avatar/Initial --}}
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-2xl flex items-center justify-center text-white font-black shadow-md shadow-indigo-100 shrink-0">
                            AE
                        </div>

                        {{-- Organizer Details --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-slate-800 truncate text-base">AmikomEventHub</p>
                        </div>
                    </div>
                </div>

                {{-- Card Penyelenggara Event --}}
                <div
                    class="p-6 bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200">
                    <h4 class="font-bold text-slate-400 text-[10px] uppercase tracking-widest mb-4">Diselenggarakan Oleh
                    </h4>

                    <div class="flex items-center gap-4">
                        {{-- Organizer Avatar/Initial (Diambil dari 2 huruf pertama nama organizer di DB) --}}
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-2xl flex items-center justify-center text-white font-black shadow-md shadow-indigo-100 shrink-0 uppercase">
                            {{ substr($event->organizer->name ?? 'EO', 0, 2) }}
                        </div>

                        {{-- Organizer Details (Nama diambil dari relasi database organizer) --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-slate-800 truncate text-base">
                                {{ $event->organizer->name ?? 'Nama Penyelenggara' }}</p>
                            <div class="flex items-center gap-1.5 text-indigo-600 mt-0.5">
                                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-[9px] font-extrabold uppercase tracking-wider">Verified Organizer</p>
                            </div>
                        </div>

                        {{-- Rating Badge (Dihitung dari data rating event di database) --}}
                        <div
                            class="bg-slate-50 border border-slate-100 rounded-2xl px-3 py-2.5 text-center shrink-0 min-w-[70px]">
                            <div class="inline-flex items-center gap-1 text-amber-500 font-bold text-sm">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.173c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.176 0l-3.38 2.455c-.784.57-1.838-.197-1.539-1.118l1.286-3.966a1 1 0 00-.364-1.118L2.05 9.393c-.783-.57-.38-1.81.588-1.81h4.173a1 1 0 00.95-.69l1.286-3.966z" />
                                </svg>
                                <span>{{ number_format($event->average_rating ?? $event->ratings->avg('score') ?? 0, 1) }}</span>
                            </div>
                            <p class="text-[10px] font-bold text-slate-400 mt-0.5">{{ $event->ratings->count() }} ulasan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= RIGHT COLUMN: EVENT INFO & TICKET CARD ================= -->
        <div class="lg:col-span-2 space-y-8">

            {{-- Event Header --}}
            <div class="space-y-4">
                <span
                    class="inline-flex items-center px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-full text-[10px] font-extrabold uppercase tracking-widest border border-indigo-100/50">
                    {{ $event->category->name ?? 'Event' }}
                </span>

                <h1 class="text-3xl md:text-5xl font-black text-slate-900 leading-tight tracking-tight">
                    {{ $event->title }}
                </h1>

                {{-- Meta Badges Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-2">
                    {{-- Date --}}
                    <div class="flex items-center gap-3 p-3.5 bg-slate-50 border border-slate-100 rounded-2xl">
                        <div class="p-2.5 bg-white text-indigo-600 rounded-xl shadow-sm shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Tanggal</p>
                            <p class="text-xs font-bold text-slate-700 truncate">
                                {{ \Carbon\Carbon::parse($event->date)->translatedFormat('l, d F Y') }}</p>
                        </div>
                    </div>

                    {{-- Time --}}
                    <div class="flex items-center gap-3 p-3.5 bg-slate-50 border border-slate-100 rounded-2xl">
                        <div class="p-2.5 bg-white text-indigo-600 rounded-xl shadow-sm shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Waktu Mulai</p>
                            <p class="text-xs font-bold text-slate-700 truncate">
                                {{ \Carbon\Carbon::parse($event->date)->format('H:i') }} WIB</p>
                        </div>
                    </div>

                    {{-- Location --}}
                    <div class="flex items-center gap-3 p-3.5 bg-slate-50 border border-slate-100 rounded-2xl">
                        <div class="p-2.5 bg-white text-indigo-600 rounded-xl shadow-sm shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Tempat</p>
                            <p class="text-xs font-bold text-slate-700 truncate">{{ $event->location }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- Deskripsi Event --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 md:p-8 space-y-4">
                <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2.5">
                    <span class="w-1.5 h-6 bg-indigo-600 rounded-full"></span>
                    Tentang Event
                </h3>
                <div
                    class="prose prose-slate max-w-none text-slate-600 leading-relaxed font-medium text-sm whitespace-pre-line">
                    {{ $event->description }}
                </div>
            </div>
            <div
                class="bg-gradient-to-br from-indigo-600 via-indigo-700 to-indigo-800 rounded-[2.5rem] p-8 md:p-12 text-white shadow-xl relative overflow-hidden">
                <div class="grid md:grid-cols-2 gap-8 items-center relative z-10">

                    {{-- Kolom kiri: harga aktif --}}
                    <div class="space-y-3">
                        <p class="text-indigo-200 font-extrabold uppercase tracking-widest text-[10px]">Harga Tiket Saat Ini
                        </p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-xl font-bold text-indigo-200">Rp</span>
                            <h2 class="text-5xl md:text-6xl font-black tracking-tight">
                                {{ number_format($event->currentPrice(), 0, ',', '.') }}
                            </h2>
                        </div>
                        <div
                            class="inline-flex items-center gap-2 px-3.5 py-2 bg-indigo-800/40 rounded-xl border border-indigo-500/30">
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <p class="text-indigo-100 text-xs font-bold">
                                Sisa stok: <span class="text-white font-black">{{ $event->stock }} Tiket</span>
                            </p>
                        </div>
                    </div>

                    {{-- Kolom kanan: daftar harga bertahap + CTA --}}
                    <div class="space-y-4">
                        <div class="space-y-2">
                            @foreach($event->ticketPrices as $ticket)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-semibold">{{ $ticket->category }}</span>
                                    <span
                                        class="text-lg font-bold 
                                {{ $ticket->category == 'Early Bird' ? 'text-green-400' : ($ticket->category == 'Presale' ? 'text-yellow-400' : 'text-red-400') }}">
                                        Rp {{ number_format($ticket->price, 0, ',', '.') }}
                                    </span>
                                    <span class="text-xs text-indigo-200">s/d
                                        {{ \Carbon\Carbon::parse($ticket->end_date)->format('d M Y') }}</span>
                                </div>
                            @endforeach
                        </div>

                        {{-- CTA Pesan --}}
                        @if($event->stock > 0)
                            <a href="{{ route('checkout.create', $event->id) }}"
                                class="block text-center px-10 py-5 bg-white text-indigo-600 rounded-2xl font-black text-lg hover:bg-slate-50 hover:shadow-lg transition">
                                Pesan Sekarang
                            </a>
                        @else
                            <button disabled
                                class="w-full px-10 py-5 bg-indigo-500/50 text-indigo-200/60 rounded-2xl font-black text-lg cursor-not-allowed opacity-60">
                                Tiket Habis
                            </button>
                        @endif
                    </div>
                </div>
            </div>


            {{-- Kebijakan Tiket --}}
            <div class="p-6 md:p-8 bg-slate-50 rounded-3xl border border-slate-100/80 space-y-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Kebijakan Tiket</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Policy item 1 --}}
                    <div class="flex items-start gap-3.5 p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
                        <div
                            class="w-8 h-8 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <p class="text-xs font-bold text-slate-600 leading-relaxed">E-Ticket dikirim otomatis setelah
                            pembayaran Anda terverifikasi sistem.</p>
                    </div>

                    {{-- Policy item 2 --}}
                    <div class="flex items-start gap-3.5 p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
                        <div
                            class="w-8 h-8 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-xs font-bold text-slate-600 leading-relaxed">Pindai kode QR tiket Anda di lokasi
                            acara untuk proses check-in mandiri.</p>
                    </div>

                    {{-- Policy item 3 (Attention) --}}
                    <div
                        class="flex items-start gap-3.5 p-4 bg-rose-50 rounded-2xl border border-rose-100/70 md:col-span-2">
                        <div class="w-8 h-8 bg-rose-100 text-rose-600 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="space-y-0.5">
                            <span class="text-[10px] font-extrabold text-rose-600 uppercase tracking-wider">Perhatian
                                Penting</span>
                            <p class="text-xs font-bold text-rose-700 leading-relaxed">Tiket yang sudah berhasil dibeli
                                tidak dapat ditukar atau dikembalikan (Non-refundable).</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= GUEST LOGIN MODAL ================= -->
        <div id="guestLoginModal"
            class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-md items-center justify-center z-50 p-6 transition-all duration-300">
            <div
                class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-sm p-8 text-center border border-slate-100/50 transform scale-95 transition-transform duration-300">
                {{-- Lock Icon Container --}}
                <div class="flex justify-center mb-5">
                    <div class="bg-indigo-50 text-indigo-600 p-4.5 rounded-full shadow-inner relative">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z">
                            </path>
                        </svg>
                    </div>
                </div>

                {{-- Modal Typography --}}
                <h2 class="text-2xl font-black text-slate-800 mb-2">Yuk, Masuk Dulu!</h2>
                <p class="text-slate-500 font-medium text-xs leading-relaxed mb-8">
                    Kamu perlu masuk ke akun <strong class="text-slate-700 font-bold">AmikomEventHub</strong> terlebih
                    dahulu sebelum bisa memesan tiket event seru ini.
                </p>

                {{-- Interactive Buttons --}}
                <div class="space-y-3">
                    <button id="guestLoginBtn"
                        class="w-full bg-indigo-600 text-white py-3.5 rounded-2xl font-bold text-sm hover:bg-indigo-700 hover:shadow-lg active:scale-[0.98] transition-all duration-150">
                        Masuk Sekarang
                    </button>
                    <a href="{{ route('register') }}"
                        class="block w-full border border-slate-200 py-3.5 rounded-2xl font-bold text-xs text-slate-600 hover:bg-slate-50 active:scale-[0.98] transition-all duration-150">
                        Belum punya akun? <span class="text-indigo-600 underline">Daftar</span>
                    </a>
                    <button id="guestCancelBtn"
                        class="text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors pt-2">
                        Batal, kembali
                    </button>
                </div>
            </div>
        </div>

    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var btn = document.getElementById('guestOrderBtn');
            var modal = document.getElementById('guestLoginModal');
            var modalContent = modal ? modal.querySelector('.transform') : null;

            if (btn && modal) {
                btn.addEventListener('click', function () {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    setTimeout(() => {
                        modalContent?.classList.remove('scale-95');
                        modalContent?.classList.add('scale-100');
                    }, 10);
                });
            }

            var cancel = document.getElementById('guestCancelBtn');
            if (cancel && modal) {
                cancel.addEventListener('click', function () {
                    modalContent?.classList.remove('scale-100');
                    modalContent?.classList.add('scale-95');
                    setTimeout(() => {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }, 150);
                });
            }

            var login = document.getElementById('guestLoginBtn');
            if (login) {
                login.addEventListener('click', function () {
                    window.location.href = "{{ route('login', ['next' => route('checkout.create', $event->id)]) }}";
                });
            }
        });
    </script>
@endsection