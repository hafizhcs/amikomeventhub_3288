@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.coupons.index') }}" class="text-sm font-medium text-slate-500 hover:text-indigo-600 flex items-center gap-1 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>
        <h1 class="text-2xl font-bold text-slate-800 mt-2">Edit Kupon: <span class="font-mono text-indigo-600">{{ $coupon->code }}</span></h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 md:p-8">
        <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Kode Kupon -->
            <div>
                <label for="code" class="block text-sm font-semibold text-slate-700 mb-2">Kode Kupon</label>
                <input type="text" name="code" id="code" value="{{ old('code', $coupon->code) }}" class="w-full px-4 py-2.5 border @error('code') border-rose-300 focus:ring-rose-200 focus:border-rose-400 @else border-slate-200 focus:ring-indigo-200 focus:border-indigo-400 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-4 uppercase font-mono tracking-wider transition">
                @error('code')
                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipe & Nilai -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="type" class="block text-sm font-semibold text-slate-700 mb-2">Tipe Diskon</label>
                    <select name="type" id="type" class="w-full px-4 py-2.5 border @error('type') border-rose-300 focus:ring-rose-200 @else border-slate-200 focus:ring-indigo-200 focus:border-indigo-400 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-4 transition bg-white">
                        <option value="percentage" {{ old('type', $coupon->type) === 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                        <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>Potongan Tetap (Rupiah)</option>
                    </select>
                    @error('type')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="value" class="block text-sm font-semibold text-slate-700 mb-2">Nilai Potongan</label>
                    <input type="number" name="value" id="value" value="{{ old('value', $coupon->value) }}" min="1" class="w-full px-4 py-2.5 border @error('value') border-rose-300 focus:ring-rose-200 focus:border-rose-400 @else border-slate-200 focus:ring-indigo-200 focus:border-indigo-400 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-4 transition">
                    @error('value')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tanggal Kedaluwarsa -->
            <div>
                <label for="expired_at" class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Kedaluwarsa (Opsional)</label>
                <input type="date" name="expired_at" id="expired_at" value="{{ old('expired_at', $coupon->expired_at ? \Carbon\Carbon::parse($coupon->expired_at)->format('Y-m-d') : '') }}" class="w-full px-4 py-2.5 border @error('expired_at') border-rose-300 focus:ring-rose-200 focus:border-rose-400 @else border-slate-200 focus:ring-indigo-200 focus:border-indigo-400 @enderror rounded-lg shadow-sm focus:outline-none focus:ring-4 transition text-slate-700">
                @error('expired_at')
                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                @enderror
                <p class="mt-1.5 text-xs text-slate-400">Kosongkan jika kupon berlaku selamanya.</p>
            </div>

            <!-- Action -->
            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.coupons.index') }}" class="px-5 py-2.5 border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 transition font-medium text-sm">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition font-medium text-sm shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection