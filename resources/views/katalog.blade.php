@extends('layouts.app')

@section('title', 'AmikomEventHub - Katalog Event')

@section('content')

<section id="events-section" class="max-w-7xl mx-auto px-6 py-20">
    <div class="flex justify-between items-end mb-10">
        <div>
            <h2 class="text-3xl font-extrabold mb-2">Event Terdekat</h2>
            <p class="text-slate-500 font-medium">Jangan sampai ketinggalan acara seru!</p>
        </div>
    </div>

    {{-- Filter kategori --}}
    <div class="flex flex-wrap gap-3 mb-10">
        <button data-category="" 
                class="category-btn px-5 py-2.5 rounded-xl font-bold transition border-2
                {{ $activeCategory == '' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-600 border-slate-200 hover:border-indigo-400 hover:text-indigo-600' }}">
           Semua Kategori
        </button>
        @foreach($categories as $cat)
        <button data-category="{{ $cat->slug }}"
                class="category-btn px-5 py-2.5 rounded-xl font-bold transition border-2
                {{ $activeCategory == $cat->slug ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-600 border-slate-200 hover:border-indigo-400 hover:text-indigo-600' }}">
           {{ $cat->name }}
        </button>
        @endforeach
    </div>

    {{-- Container Wrapper Utama untuk Event --}}
    <div id="events-container-wrapper">
        @if($events->isEmpty())
            <div class="text-center py-20">
                <div class="text-6xl mb-4">😕</div>
                <h3 class="text-2xl font-bold text-slate-700 mb-2">Tidak ada event ditemukan</h3>
                <p class="text-slate-500 mb-6">Coba pilih kategori lain atau lihat semua event.</p>
                <a href="{{ route('home') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition">
                    Lihat Semua Event
                </a>
            </div>
        @else
            <div id="events-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($events as $event)
                    @php
                        // Cek apakah event sudah lewat tanggalnya hari ini
                        $isPast = \Carbon\Carbon::parse($event->date)->isPast();
                    @endphp
                    
                    <div class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden relative flex flex-col justify-between {{ $isPast ? 'opacity-70' : '' }}">
                        <div>
                            {{-- Image Container Fixed w-full --}}
                            <div class="relative w-full overflow-hidden aspect-[3/4] bg-slate-100">
                                <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                                             ? asset('storage/' . $event->poster_path)
                                             : 'https://placehold.co/600x800' }}"
                                     alt="{{ $event->title }}"
                                     class="w-full h-full object-cover {{ $isPast ? 'grayscale filter brightness-75' : 'group-hover:scale-110 transition-transform duration-500' }}">

                                {{-- Kondisi Badge: Jika Past tampilkan SELESAI, jika tidak tampilkan Kategori --}}
                                @if($isPast)
                                    <div class="absolute top-4 left-4 px-3 py-1 bg-slate-800/90 text-white backdrop-blur-sm rounded-lg text-xs font-bold uppercase z-10 tracking-wider">
                                        🔒 Selesai
                                    </div>
                                @else
                                    <div class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-lg text-xs font-bold uppercase text-indigo-600 z-10">
                                        {{ $event->category->name ?? 'Event' }}
                                    </div>
                                @endif
                                
                                @if($event->reviews_count > 0 && !$isPast)
                                    <div class="absolute right-4 top-4 inline-flex items-center gap-2 rounded-full bg-white/95 backdrop-blur-sm px-3 py-2 text-xs font-semibold text-slate-900 shadow-sm ring-1 ring-slate-200 z-10">
                                        <svg class="w-3.5 h-3.5 text-amber-500" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.173c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.176 0l-3.38 2.455c-.784.57-1.838-.197-1.539-1.118l1.286-3.966a1 1 0 00-.364-1.118L2.05 9.393c-.783-.57-.38-1.81.588-1.81h4.173a1 1 0 00.95-.69l1.286-3.966z"/></svg>
                                        <span>{{ number_format($event->average_rating ?? 0, 1) }}</span>
                                    </div>
                                @endif
                                
                                @if($event->price == 0 && !$isPast)
                                    <div class="absolute bottom-4 right-4 px-3 py-1 bg-green-500 text-white rounded-lg text-xs font-bold uppercase z-10">
                                        GRATIS
                                    </div>
                                @endif
                            </div>
                            
                            <div class="p-6">
                                <h3 class="text-xl font-bold mb-2 transition line-clamp-2 {{ $isPast ? 'text-slate-400 line-through' : 'group-hover:text-indigo-600' }}">{{ $event->title }}</h3>
                                <p class="text-sm text-slate-500 mb-1">{{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }} WIB</p>
                                <p class="text-sm text-slate-500 mb-4 line-clamp-1">{{ $event->location }}</p>
                            </div>
                        </div>

                        <div class="p-6 pt-0">
                            <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                                <div>
                                    <span class="text-xl font-black {{ $isPast ? 'text-slate-400' : 'text-indigo-600' }}">
                                        {{ $event->price == 0 ? 'GRATIS' : 'Rp ' . number_format($event->price, 0, ',', '.') }}
                                    </span>
                                    <p class="text-xs text-slate-400">{{ $isPast ? 'Pendaftaran ditutup' : $event->stock . ' tiket tersisa' }}</p>
                                </div>
                                
                                @if($isPast)
                                    <button disabled class="px-5 py-2 bg-slate-100 text-slate-400 rounded-xl font-bold cursor-not-allowed whitespace-nowrap">Selesai</button>
                                @else
                                    <a href="{{ route('events.show', $event->id) }}" class="px-5 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-600 hover:text-white transition whitespace-nowrap">Lihat Detail</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

@endsection

<script>
document.addEventListener('DOMContentLoaded', () => {
    const categoryButtons = document.querySelectorAll('.category-btn');
    const wrapper = document.getElementById('events-container-wrapper');

    if (!wrapper) return;

    categoryButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const currentBtn = e.currentTarget;
            const category = currentBtn.dataset.category;

            categoryButtons.forEach(b => {
                b.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600');
                b.classList.add('bg-white', 'text-slate-600', 'border-slate-200');
            });
            currentBtn.classList.remove('bg-white', 'text-slate-600', 'border-slate-200');
            currentBtn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600');

            wrapper.style.opacity = '0.4';
            wrapper.style.transition = 'opacity 0.2s ease';

            fetch(`/katalog?category=${category}`, { 
                headers: { 'X-Requested-With': 'XMLHttpRequest' } 
            })
            .then(res => {
                if (!res.ok) throw new Error('Gagal memuat data');
                return res.json();
            })
            .then(data => {
                wrapper.style.opacity = '1';

                if (!data.events || data.events.length === 0) {
                    wrapper.innerHTML = `
                        <div class="text-center py-20 animate-fade-in">
                            <div class="text-6xl mb-4">😕</div>
                            <h3 class="text-2xl font-bold text-slate-700 mb-2">Tidak ada event ditemukan</h3>
                            <p class="text-slate-500 mb-6">Coba pilih kategori lain atau lihat semua event.</p>
                            <a href="/katalog" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition">
                                Lihat Semua Event
                            </a>
                        </div>`;
                } else {
                    let gridHtml = `<div id="events-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 animate-fade-in">`;
                    
                    // Client-side date comparison
                    const now = new Date();

                    data.events.forEach(event => {
                        const posterUrl = event.poster_path ? `/storage/${event.poster_path}` : 'https://placehold.co/600x800';
                        
                        // Konversi string date dari backend ke date object js
                        const eventDate = new Date(event.date);
                        const isPast = eventDate < now;

                        let badgeHtml = '';
                        let imgClass = 'w-full h-full object-cover ';
                        let titleClass = 'text-xl font-bold mb-2 transition line-clamp-2 ';
                        let actionBtnHtml = '';
                        let priceClass = 'text-xl font-black ';
                        let stockText = `${event.stock} tiket tersisa`;

                        if (isPast) {
                            badgeHtml = `<div class="absolute top-4 left-4 px-3 py-1 bg-slate-800/90 text-white backdrop-blur-sm rounded-lg text-xs font-bold uppercase z-10 tracking-wider">🔒 Selesai</div>`;
                            imgClass += 'grayscale filter brightness-75';
                            titleClass += 'text-slate-400 line-through';
                            priceClass += 'text-slate-400';
                            stockText = 'Pendaftaran ditutup';
                            actionBtnHtml = `<button disabled class="px-5 py-2 bg-slate-100 text-slate-400 rounded-xl font-bold cursor-not-allowed whitespace-nowrap">Selesai</button>`;
                        } else {
                            badgeHtml = `<div class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-lg text-xs font-bold uppercase text-indigo-600 z-10">${event.category ? event.category.name : 'Event'}</div>`;
                            imgClass += 'group-hover:scale-110 transition-transform duration-500';
                            titleClass += 'group-hover:text-indigo-600';
                            priceClass += 'text-indigo-600';
                            actionBtnHtml = `<a href="/events/${event.id}" class="px-5 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-600 hover:text-white transition whitespace-nowrap">Lihat Detail</a>`;
                        }

                        const ratingBadge = (event.reviews_count > 0 && !isPast) ? `
                            <div class="absolute right-4 top-4 inline-flex items-center gap-2 rounded-full bg-white/95 backdrop-blur-sm px-3 py-2 text-xs font-semibold text-slate-900 shadow-sm ring-1 ring-slate-200 z-10">
                                <svg class="w-3.5 h-3.5 text-amber-500" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.173c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.176 0l-3.38 2.455c-.784.57-1.838-.197-1.539-1.118l1.286-3.966a1 1 0 00-.364-1.118L2.05 9.393c-.783-.57-.38-1.81.588-1.81h4.173a1 1 0 00.95-.69l1.286-3.966z"/></svg>
                                <span>${Number(event.average_rating || 0).toFixed(1)}</span>
                            </div>` : '';

                        const gratisBadge = (event.price == 0 && !isPast) ? `
                            <div class="absolute bottom-4 right-4 px-3 py-1 bg-green-500 text-white rounded-lg text-xs font-bold uppercase z-10">
                                GRATIS
                            </div>` : '';

                        const formattedDate = eventDate.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + 
                                              ', ' + eventDate.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

                        const formattedPrice = event.price == 0 ? 'GRATIS' : 'Rp ' + Number(event.price).toLocaleString('id-ID', { minimumFractionDigits: 0 });

                        gridHtml += `
                            <div class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden relative flex flex-col justify-between ${isPast ? 'opacity-70' : ''}">
                                <div>
                                    <div class="relative w-full overflow-hidden aspect-[3/4] bg-slate-100">
                                        <img src="${posterUrl}" alt="${event.title}" class="${imgClass}">
                                        ${badgeHtml}
                                        ${ratingBadge}
                                        ${gratisBadge}
                                    </div>
                                    <div class="p-6">
                                        <h3 class="${titleClass}">${event.title}</h3>
                                        <p class="text-sm text-slate-500 mb-1">${formattedDate} WIB</p>
                                        <p class="text-sm text-slate-500 mb-4 line-clamp-1">${event.location}</p>
                                    </div>
                                </div>
                                <div class="p-6 pt-0">
                                    <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                                        <div>
                                            <span class="${priceClass}">${formattedPrice}</span>
                                            <p class="text-xs text-slate-400">${stockText}</p>
                                        </div>
                                        ${actionBtnHtml}
                                    </div>
                                </div>
                            </div>`;
                    });

                    gridHtml += `</div>`;
                    wrapper.innerHTML = gridHtml;
                }
            })
            .catch(err => {
                console.error(err);
                wrapper.style.opacity = '1';
                wrapper.innerHTML = '<p class="text-center py-10 text-red-500 animate-fade-in">Gagal memuat data event. Silakan coba lagi.</p>';
            });
        });
    });
});
</script>