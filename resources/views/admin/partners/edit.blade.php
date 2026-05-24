@extends('layouts.admin')

@section('title', 'Edit Partner')
@section('page_title', 'Edit Partner')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-700">Nama Partner</label>
            <input type="text" name="name" id="name" class="mt-1 px-4 py-2 border rounded-lg w-full"
                   value="{{ old('name', $partner->name) }}" required>
        </div>
        <div class="mb-4">
            <label for="logo" class="block text-sm font-medium text-slate-700">Logo Partner</label>
            @if($partner->logo)
                <img src="{{ asset('storage/' . $partner->logo) }}" alt="{{ $partner->name }}" class="h-16 mb-2">
            @endif
            <input type="file" name="logo" id="logo" class="mt-1 w-full border rounded-lg px-4 py-2">
        </div>
        <div class="mb-4">
            <label for="category_id" class="block text-sm font-medium text-slate-700">Kategori</label>
            <select name="category_id" id="category_id" class="mt-1 px-4 py-2 border rounded-lg w-full">
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $partner->category_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg">Update</button>
        <a href="{{ route('admin.partners.index') }}" class="px-6 py-2 bg-slate-200 rounded-lg">Batal</a>
    </form>
</div>
@endsection
