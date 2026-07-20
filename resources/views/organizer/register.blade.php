@extends('layouts.app')

@section('title', 'Daftarkan Organisasi - AmikomEventHub')

@section('content')
<main class="max-w-2xl mx-auto px-6 py-16">

    <div class="bg-white rounded-3xl border border-slate-200 p-10 shadow-sm">
        <h1 class="text-2xl font-black mb-2">Daftarkan Organisasi / HIMA</h1>
        <p class="text-slate-500 mb-8">
            Ajukan organisasi Anda untuk bisa membuat dan menjual tiket event sendiri di AmikomEventHub.
            Pengajuan akan diverifikasi oleh Superadmin terlebih dahulu.
        </p>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-600 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('organizer.register.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Nama Organisasi</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Contoh: HIMA Informatika">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Deskripsi (opsional)</label>
                <textarea name="description" rows="3"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Email Kontak</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email') }}" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">No. Telepon/WA</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <p class="text-sm font-bold text-slate-700 pt-2">Rekening Pencairan Dana (opsional, bisa dilengkapi nanti)</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <input type="text" name="bank_name" value="{{ old('bank_name') }}" placeholder="Nama Bank"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}" placeholder="No. Rekening"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <input type="text" name="bank_account_name" value="{{ old('bank_account_name') }}" placeholder="Atas Nama"
                    class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <button type="submit"
                class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-lg hover:bg-indigo-700 transition">
                Ajukan Pendaftaran
            </button>
        </form>
    </div>
</main>
@endsection
