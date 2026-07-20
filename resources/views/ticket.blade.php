@extends('layouts.app')

@section('title', 'Tiket Saya - AmikomEventHub')

@section('content')
    <div class="min-h-screen bg-slate-50/50 py-12 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="mb-8">
                <h1 class="text-4xl font-black text-slate-900 tracking-tight">Tiket Saya</h1>
                <p class="text-slate-500 mt-2">Daftar semua tiket event yang telah Anda pesan.</p>
            </div>

            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/75">
                                <th class="py-5 px-8 text-xs font-bold uppercase tracking-wider text-slate-400">Order ID
                                </th>
                                <th class="py-5 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Event</th>
                                <th class="py-5 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Tgl
                                    Pemesanan</th>
                                <th class="py-5 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Total Bayar
                                </th>
                                <th class="py-5 px-6 text-xs font-bold uppercase tracking-wider text-slate-400">Status</th>
                                <th class="py-5 px-8 text-xs font-bold uppercase tracking-wider text-slate-400 text-right">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($transactions as $transaction)
                                                <tr class="hover:bg-slate-50/50 transition-colors">
                                                    <td class="py-6 px-8 font-extrabold text-slate-800 text-sm">
                                                        {{ $transaction->order_id }}
                                                    </td>

                                                    <td class="py-6 px-6">
                                                        <div class="flex items-center gap-4">
                                                            <img src="{{ (optional($transaction->event)->poster_path && Storage::disk('public')->exists($transaction->event->poster_path))
                                ? asset('storage/' . $transaction->event->poster_path)
                                : 'https://placehold.co/150' }}"
                                                                alt="{{ optional($transaction->event)->title }}"
                                                                class="w-12 h-12 rounded-2xl object-cover shadow-sm">
                                                            <div>
                                                                <p class="font-extrabold text-slate-900 text-sm">
                                                                    {{ optional($transaction->event)->title ?? '-' }}
                                                                </p>
                                                                <p class="text-xs text-slate-400 mt-0.5">
                                                                    {{ optional($transaction->event)->date?->format('d F Y') ?? '-' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="py-6 px-6 text-sm text-slate-500 font-medium">
                                                        {{ $transaction->created_at->format('d M Y, H:i') }}
                                                    </td>

                                                    <td class="py-6 px-6 text-sm font-extrabold text-slate-900">
                                                        Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                                                    </td>

                                                    <td class="py-6 px-6">
                                                        @if(strtolower($transaction->status) === 'success')
                                                            <span
                                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                                                SUCCESS
                                                            </span>
                                                        @else
                                                            <span
                                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100">
                                                                {{ strtoupper($transaction->status) }}
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td class="py-6 px-8 text-right">
                                                        @if(strtolower($transaction->status) === 'success')
                                                            <a href="{{ route('eticket.show', $transaction->id) }}"
                                                                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.98] text-white text-xs font-bold rounded-xl shadow-md shadow-indigo-100 transition-all whitespace-nowrap min-w-[140px]">
                                                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                    </path>
                                                                </svg>
                                                                <span>Lihat E-Ticket</span>
                                                            </a>
                                                        @else
                                                            <button disabled
                                                                class="px-4 py-2.5 bg-slate-100 text-slate-400 text-xs font-bold rounded-xl cursor-not-allowed">
                                                                Belum Tersedia
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-slate-400 font-medium">
                                        Belum ada transaksi tiket yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection