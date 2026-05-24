@extends('layouts.admin')

@section('title', 'Tambah Partner')
@section('page_title', 'Tambah Partner')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-700">Nama Partner</label>
            <input type="text" name="name" id="name" class="mt-1 px-4 py-2 border rounded-lg w-full"
                   placeholder="Masukkan nama partner" required>
        </div>
        <div class="mb-4">
            <label for="logo" class="block text-sm font-medium text-slate-700">Logo Partner</label>
            <input type="file" name="logo" id="logo" class="mt-1 w-full border rounded-lg px-4 py-2">
        </div>
        <div class="mb-4">
            <label for="category_id" class="block text-sm font-medium text-slate-700">Kategori</label>
            <select name="category_id" id="category_id" class="mt-1 px-4 py-2 border rounded-lg w-full">
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg">Simpan</button>
        <a href="{{ route('admin.partners.index') }}" class="px-6 py-2 bg-slate-200 rounded-lg">Batal</a>
    </form>
</div>
@endsection
