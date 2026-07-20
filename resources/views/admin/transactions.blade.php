@extends('layouts.admin')

@section('title', 'Laporan Transaksi - Admin')
@section('page_title', 'Laporan Transaksi')
@section('page_subtitle', 'Pantau arus kas dan penjualan tiket Anda.')

@section('content')
    <div class="flex gap-4">
        <button class="px-6 py-3 border-2 border-slate-200 rounded-2xl font-bold hover:bg-white hover:border-indigo-600 hover:text-indigo-600 transition">Ekspor Excel</button>
        <button class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg hover:bg-indigo-700 transition">Unduh PDF</button>
    </div>

<div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="px-8 py-6 bg-slate-50/50 border-b flex flex-wrap gap-4 items-center">
        <input type="text" placeholder="Cari Order ID, Nama, atau Email..."
            class="flex-1 min-w-[300px] px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition text-sm font-medium">
        <div class="flex gap-2">
            <select class="px-5 py-3 rounded-xl border-slate-200 border bg-white outline-none text-sm font-bold">
                <option>Semua Status</option>
                <option>Success</option>
                <option>Pending</option>
                <option>Expired</option>
            </select>
            <select class="px-5 py-3 rounded-xl border-slate-200 border bg-white outline-none text-sm font-bold">
                <option>Bulan Ini</option>
                <option>Bulan Lalu</option>
                <option>Tahun 2024</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">Order ID</th>
                    <th class="px-8 py-4">Detail Pembeli</th>
                    <th class="px-8 py-4">Event</th>
                    <th class="px-8 py-4">Tgl Transaksi</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4 text-right">Total Tagihan</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
    @foreach($transactions as $trx)
    <tr class="hover:bg-slate-50/50 transition">
        <td class="px-8 py-6">
            <span class="font-mono font-bold 
                @if($trx->status == 'success') text-indigo-600 bg-indigo-50
                @elseif($trx->status == 'pending') bg-orange-100 text-orange-700
                @elseif($trx->status == 'free') bg-slate-100 text-slate-600
                @else bg-rose-100 text-rose-700 @endif
                px-3 py-1 rounded-lg text-sm">
                #{{ $trx->order_id }}
            </span>
        </td>
        <td class="px-8 py-6">
            <p class="font-bold text-slate-800">{{ $trx->customer_name }}</p>
            <p class="text-xs text-slate-500">{{ $trx->customer_email }}</p>
        </td>
        <td class="px-8 py-6">
            <p class="font-medium text-slate-700">{{ $trx->event->title ?? '-' }}</p>
        </td>
        <td class="px-8 py-6 text-sm text-slate-500">
            {{ $trx->created_at->format('d M Y, H:i') }}
        </td>
        <td class="px-8 py-6">
            <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase ring-1
                @if($trx->status == 'success') bg-green-100 text-green-700 ring-green-200
                @elseif($trx->status == 'pending') bg-orange-100 text-orange-700 ring-orange-200
                @elseif($trx->status == 'free') bg-slate-100 text-slate-600 ring-slate-200
                @else bg-rose-100 text-rose-700 ring-rose-200 @endif">
                {{ ucfirst($trx->status) }}
            </span>
        </td>
        <td class="px-8 py-6 text-right font-black text-slate-900">
            Rp {{ number_format($trx->total_price, 0, ',', '.') }}
        </td>
    </tr>
    @endforeach
</tbody>

        </table>
    </div>

    <div class="px-8 py-6 bg-slate-50/50 border-t flex justify-between items-center">
        <p class="text-sm text-slate-500 font-medium">Menampilkan 2 dari 124 transaksi</p>
        <div class="flex gap-2">
            <button class="px-4 py-2 border rounded-xl text-sm font-bold opacity-50 cursor-not-allowed">Previous</button>
            <button class="px-4 py-2 bg-indigo-600 text-white rounded-xl shadow-md text-sm font-bold">1</button>
            <button class="px-4 py-2 border rounded-xl hover:bg-white transition text-sm font-bold">2</button>
            <button class="px-4 py-2 border rounded-xl hover:bg-white transition text-sm font-bold">Next</button>
        </div>
    </div>
</div>
@endsection