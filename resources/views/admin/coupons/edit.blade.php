@extends('layouts.admin')
@section('title','Edit Coupon')
@section('page_title','Edit Coupon')

@section('content')
<div class="container mx-auto px-6 py-6">
    <h1 class="text-2xl font-bold mb-4">Edit Coupon</h1>

    <form action="{{ route('admin.coupons.update',$coupon->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Kode Coupon --}}
        <div class="mb-5">
            <label class="block font-semibold mb-2">Kode Coupon</label>
            <input type="text" name="code" value="{{ old('code', $coupon->code) }}"
                   class="w-full border rounded-lg px-4 py-2 uppercase">
            @error('code') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Tipe Diskon --}}
        <div class="mb-5">
            <label class="block font-semibold mb-2">Tipe Diskon</label>
            <select id="discountType" name="type" class="w-full border rounded-lg px-4 py-2">
                <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
            </select>
            @error('type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Nilai Diskon --}}
        <div class="mb-5">
            <label class="block font-semibold mb-2">Nilai Diskon <span id="discountLabel"></span></label>
            <input type="number" step="0.01" name="value" value="{{ old('value', $coupon->value) }}"
                   class="w-full border rounded-lg px-4 py-2">
            @error('value') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Tanggal Berakhir --}}
        <div class="mb-6">
            <label class="block font-semibold mb-2">Tanggal Berakhir</label>
            <input type="date" name="expired_at"
                   value="{{ old('expired_at', $coupon->expired_at ? \Carbon\Carbon::parse($coupon->expired_at)->format('Y-m-d') : '') }}"
                   class="w-full border rounded-lg px-4 py-2">
            @error('expired_at') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.coupons.index') }}" class="bg-gray-500 text-white px-5 py-2 rounded-lg">Batal</a>
            <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg">Update</button>
        </div>
    </form>
</div>

{{-- Script untuk ubah label --}}
<script>
    const typeSelect = document.getElementById('discountType');
    const labelSpan = document.getElementById('discountLabel');

    function updateLabel() {
        if (typeSelect.value === 'percentage') {
            labelSpan.textContent = '(%)';
        } else {
            labelSpan.textContent = '(Rp)';
        }
    }

    typeSelect.addEventListener('change', updateLabel);
    updateLabel();
</script>
@endsection
