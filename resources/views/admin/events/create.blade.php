@extends('layouts.admin')

@section('title', 'Tambah Event Baru - Admin')
@section('page_title', 'Tambah Event Baru')
@section('page_subtitle', 'Masukkan detail acara baru yang akan diselenggarakan.')

@section('content')

{{-- Dibuat w-full tanpa batasan max-width agar memenuhi layar --}}
<div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm w-full">

    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-2xl border border-gray- 200 mt-2 shadow-sm">
        @csrf

        {{-- Judul Event --}}
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Judul Event</label>
            <input type="text" name="title" value="{{ old('title') }}"
                placeholder="Contoh: Jazz Night 2026"
                class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium @error('title') border-rose-400 @enderror"
                required>
            @error('title')
                <span class="text-rose-500 text-sm mt-1 block">⚠ {{ $message }}</span>
            @enderror
        </div>

        {{-- Kategori --}}
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Kategori</label>
            <select name="category_id"
                class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium @error('category_id') border-rose-400 @enderror"
                required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <span class="text-rose-500 text-sm mt-1 block">⚠ {{ $message }}</span>
            @enderror
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Deskripsi Event</label>
            <textarea name="description" rows="6"
                placeholder="Ceritakan detail acara ini..."
                class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium resize-none @error('description') border-rose-400 @enderror">{{ old('description') }}</textarea>
            @error('description')
                <span class="text-rose-500 text-sm mt-1 block">⚠ {{ $message }}</span>
            @enderror
        </div>

        {{-- Tanggal & Lokasi --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Tanggal & Waktu</label>
                <input type="datetime-local" name="date" value="{{ old('date') }}"
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium @error('date') border-rose-400 @enderror"
                    required>
                @error('date')
                    <span class="text-rose-500 text-sm mt-1 block">⚠ {{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Lokasi</label>
                <input type="text" name="location" value="{{ old('location') }}"
                    placeholder="Contoh: Auditorium AMIKOM"
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium @error('location') border-rose-400 @enderror"
                    required>
                @error('location')
                    <span class="text-rose-500 text-sm mt-1 block">⚠ {{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Harga & Stok --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Harga (Rp) — Isi 0 jika Gratis</label>
                <input type="number" name="price" value="{{ old('price', 0) }}"
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium @error('price') border-rose-400 @enderror"
                    required min="0">
                @error('price')
                    <span class="text-rose-500 text-sm mt-1 block">⚠ {{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Kapasitas (Stok Tiket)</label>
                <input type="number" name="stock" value="{{ old('stock', 100) }}"
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium @error('stock') border-rose-400 @enderror"
                    required min="1">
                @error('stock')
                    <span class="text-rose-500 text-sm mt-1 block">⚠ {{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Poster --}}
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Poster Event (Opsional)</label>
            <div class="group relative">
                <input type="file" name="poster" accept="image/*"
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
            </div>
            <p class="text-xs text-slate-400 mt-2 ml-1">Format: JPG, PNG, WEBP. Maks: 2MB.</p>
            @error('poster')
                <span class="text-rose-500 text-sm mt-1 block">⚠ {{ $message }}</span>
            @enderror
        </div>

        {{-- Tombol Aksi --}}
        <div class="pt-6 flex justify-end gap-4 border-t border-slate-100">
            <a href="{{ route('admin.events.index') }}"
                class="px-8 py-4 text-slate-500 font-bold hover:text-slate-800 transition rounded-2xl hover:bg-slate-100">
                Batal
            </a>
            <button type="submit"
                class="px-10 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 active:scale-95 transition">
                Simpan Event
            </button>
        </div>

    </form>
</div>

@endsection