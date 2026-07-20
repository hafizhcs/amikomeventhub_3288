@extends('layouts.app')

@section('title', 'AmikomEventHub - Kontak')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-20 animate-fade-in">
    <div class="mb-12 max-w-2xl">
        <h1 class="text-4xl font-black text-slate-900 mb-3 tracking-tight">Hubungi Kami</h1>
        <p class="text-slate-500 font-medium text-lg">Punya pertanyaan seputar event atau ingin bekerja sama? Silakan kirimkan pesan Anda atau hubungi kami melalui saluran resmi di bawah ini.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
        
        <div class="lg:col-span-5 space-y-6">
            
            {{-- Card Email --}}
            <div class="group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 flex items-start gap-5">
                <div class="p-3.5 bg-indigo-50 text-indigo-600 rounded-2xl group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-1.5.586m16.5 6.75L12 14.25 3.75 7.5"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">Email Resmi</h3>
                    <a href="mailto:carnelhafizh@email.com" class="text-lg font-bold text-slate-800 hover:text-indigo-600 transition break-all">
                        carnelhafizh@email.com
                    </a>
                    <p class="text-xs text-slate-400 mt-1">Dibalas dalam waktu kurang dari 24 jam kerja.</p>
                </div>
            </div>

            {{-- Card WhatsApp / Telepon --}}
            <div class="group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 flex items-start gap-5">
                <div class="p-3.5 bg-green-50 text-green-600 rounded-2xl group-hover:bg-green-600 group-hover:text-white transition-colors duration-300 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.824-1.557-5.107-3.839-6.664-6.664l1.293-.97c.361-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">WhatsApp Hotline</h3>
                    <a href="https://wa.me/628904013802" target="_blank" class="text-lg font-bold text-slate-800 hover:text-green-600 transition">
                        +62 895-0401-3802
                    </a>
                    <p class="text-xs text-slate-400 mt-1">Senin - Jumat | 08:00 - 17:00 WIB</p>
                </div>
            </div>

            {{-- Card Lokasi --}}
            <div class="group bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 flex items-start gap-5">
                <div class="p-3.5 bg-amber-50 text-amber-600 rounded-2xl group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">Sekretariat Utama</h3>
                    <p class="text-base font-bold text-slate-800 leading-snug">
                        kepooooooo<br>
                        <span class="text-sm font-medium text-slate-500">Yogyakarta</span>
                    </p>
                </div>
            </div>

        </div>

        <div class="lg:col-span-7 bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
            <h2 class="text-2xl font-bold text-slate-800 mb-6">Kirim Pesan Langsung</h2>
            
            <form action="#" method="POST" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                        <input type="text" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition text-slate-800 text-sm font-medium" placeholder="Nama Anda...">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat Email</label>
                        <input type="email" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition text-slate-800 text-sm font-medium" placeholder="nama@email.com">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Subjek / Perihal</label>
                    <input type="text" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition text-slate-800 text-sm font-medium" placeholder="Contoh: Kerja Sama Kerja Sama / Pengajuan Event">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Isi Pesan</label>
                    <textarea rows="4" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition text-slate-800 text-sm font-medium resize-none" placeholder="Tuliskan pesan Anda secara detail di sini..."></textarea>
                </div>

                <button type="submit" class="w-full md:w-auto px-6 py-3.5 bg-indigo-600 text-white rounded-xl font-bold text-sm hover:bg-indigo-700 transition shadow-md hover:shadow-indigo-200 hover:-translate-y-0.5 duration-200 flex items-center justify-center gap-2">
                    <span>Kirim Pesan</span>
                    <svg class="w-4 0 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.768 59.768 0 013.27 20.875L6 12zm0 0h7.5"></path>
                    </svg>
                </button>
            </form>
        </div>

    </div>
</div>

@endsection