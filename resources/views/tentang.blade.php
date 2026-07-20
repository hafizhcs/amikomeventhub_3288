@extends('layouts.app')

@section('title', 'AmikomEventHub - Bantuan')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-20 animate-fade-in">
    <div class="mb-14 max-w-xl">
        <h1 class="text-4xl font-black text-slate-900 mb-3 tracking-tight">Tentang Platform</h1>
        <p class="text-slate-500 font-medium text-base">Pelajari lebih lanjut mengenai platform kami dan panduan lengkap cara pemesanan tiket event.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
        
        <div class="space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.063.852l-.708 2.836a.75.75 0 001.063.852l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">Informasi Umum</h2>
            </div>

            <div class="space-y-4">
                {{-- Card Tentang Website --}}
                <div class="group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-indigo-100 transition-all duration-300">
                    <h3 class="font-bold text-lg text-slate-800 mb-2 group-hover:text-indigo-600 transition-colors">Apa itu website ini?</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Website ini adalah platform pusat informasi event yang dirancang khusus untuk memudahkan Anda menjelajahi, mendaftar, dan memantau berbagai acara menarik secara digital dan terintegrasi.</p>
                </div>

                {{-- Card Tambahan Informasi --}}
                <div class="group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-indigo-100 transition-all duration-300">
                    <h3 class="font-bold text-lg text-slate-800 mb-2 group-hover:text-indigo-600 transition-colors">Siapa saja yang bisa ikut?</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Seluruh civitas akademika dan masyarakat umum dapat bergabung. Kami menyediakan kategori event yang luas mulai dari workshop teknologi, webinar nasional, hingga kompetisi.</p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.116 60.116 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">Cara Pemesanan</h2>
            </div>

            <div class="space-y-4">
                {{-- Card Stepper Panduan --}}
                <div class="group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:border-emerald-100 transition-all duration-300">
                    <h3 class="font-bold text-lg text-slate-800 mb-4 group-hover:text-emerald-600 transition-colors">Langkah Mudah Mendaftar Event</h3>
                    
                    <div class="relative pl-6 border-l-2 border-slate-100 space-y-5">
                        {{-- Step 1 --}}
                        <div class="relative">
                            <span class="absolute -left-[31px] top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-emerald-500 ring-4 ring-white"></span>
                            <h4 class="font-semibold text-sm text-slate-800 mb-0.5">Pilih Event</h4>
                            <p class="text-xs text-slate-500">Buka menu <strong>Katalog Event</strong> dan cari acara yang Anda minati.</p>
                        </div>
                        
                        {{-- Step 2 --}}
                        <div class="relative">
                            <span class="absolute -left-[31px] top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-emerald-500 ring-4 ring-white"></span>
                            <h4 class="font-semibold text-sm text-slate-800 mb-0.5">Lihat Detail & Isi Data</h4>
                            <p class="text-xs text-slate-500">Klik <strong>Lihat Detail</strong> untuk membaca syarat, kuota tiket, dan jadwal.</p>
                        </div>

                        {{-- Step 3 --}}
                        <div class="relative">
                            <span class="absolute -left-[31px] top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-emerald-500 ring-4 ring-white"></span>
                            <h4 class="font-semibold text-sm text-slate-800 mb-0.5">Konfirmasi Tiket</h4>
                            <p class="text-xs text-slate-500">Klik <strong>Daftar Sekarang</strong>. E-tiket otomatis tersimpan di dasbor akun Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
