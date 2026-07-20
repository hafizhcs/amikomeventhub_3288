@extends('layouts.organizer')

@section('title', 'Edit Event')
@section('page_title', 'Edit Event: ' . $event->title)

@section('content')

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-8 max-w-2xl">

    @if ($event->status === 'rejected' && $event->rejection_reason)
        <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-600 text-sm">
            <strong>Ditolak Superadmin:</strong> {{ $event->rejection_reason }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-600 text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('organizer.events.update', $event) }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-1">Kategori</label>
            <select name="category_id" required class="w-full border border-slate-200 rounded-xl px-4 py-3">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-1">Judul Event</label>
            <input type="text" name="title" value="{{ old('title', $event->title) }}" required class="w-full border border-slate-200 rounded-xl px-4 py-3">
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-1">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full border border-slate-200 rounded-xl px-4 py-3">{{ old('description', $event->description) }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Tanggal & Waktu</label>
                <input type="datetime-local" name="date" value="{{ old('date', $event->date->format('Y-m-d\TH:i')) }}" required class="w-full border border-slate-200 rounded-xl px-4 py-3">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Lokasi</label>
                <input type="text" name="location" value="{{ old('location', $event->location) }}" required class="w-full border border-slate-200 rounded-xl px-4 py-3">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Harga Tiket (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $event->price) }}" min="0" required class="w-full border border-slate-200 rounded-xl px-4 py-3">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Stok Tiket</label>
                <input type="number" name="stock" value="{{ old('stock', $event->stock) }}" min="1" required class="w-full border border-slate-200 rounded-xl px-4 py-3">
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-1">Poster (kosongkan jika tidak diganti)</label>
            <input type="file" name="poster" accept="image/*" class="w-full border border-slate-200 rounded-xl px-4 py-3">
        </div>

        <button type="submit" class="w-full py-4 bg-emerald-600 text-white rounded-2xl font-black text-lg hover:bg-emerald-700 transition">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection
