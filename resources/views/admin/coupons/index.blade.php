@extends('layouts.admin')

@section('title', 'Kelola Coupon')
@section('page_title', 'Kelola Coupon')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="mb-4">
        <form action="{{ route('admin.coupons.index') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari coupon..."
                   class="px-4 py-2 border rounded-lg w-64 focus:ring focus:ring-indigo-300">
            <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Search
            </button>
        </form>
    </div>

    <a href="{{ route('admin.coupons.create') }}"
       class="px-4 py-2 bg-indigo-600 text-white rounded-lg">+ Tambah Coupon</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-slate-50 text-slate-400 uppercase text-xs font-bold">
            <tr>
                <th class="px-6 py-3">ID</th>
                <th class="px-6 py-3">Kode</th>
                <th class="px-6 py-3">Diskon</th>
                <th class="px-6 py-3">Tanggal Berakhir</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Dibuat</th>
                <th class="px-6 py-3">Diupdate</th>
                <th class="px-6 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($coupons as $coupon)
            <tr class="hover:bg-slate-50">
                <td class="px-6 py-3">{{ $coupon->id }}</td>
                <td class="px-6 py-3">{{ $coupon->code }}</td>
                <td class="px-6 py-3">
                    @if($coupon->type === 'percentage')
                        {{ $coupon->value }}%
                    @else
                        Rp {{ number_format($coupon->value, 0, ',', '.') }}
                    @endif
                </td>
                <td class="px-6 py-3">
                    {{ $coupon->expired_at ? \Carbon\Carbon::parse($coupon->expired_at)->format('d M Y') : '-' }}
                </td>
                <td class="px-6 py-3">
                    @if($coupon->expired_at && now()->lte($coupon->expired_at))
                        <span class="px-2 py-1 bg-green-100 text-green-600 rounded">Aktif</span>
                    @else
                        <span class="px-2 py-1 bg-rose-100 text-rose-600 rounded">Expired</span>
                    @endif
                </td>
                <td class="px-6 py-3">{{ $coupon->created_at ? $coupon->created_at->format('d M Y H:i') : '-' }}</td>
                <td class="px-6 py-3">{{ $coupon->updated_at ? $coupon->updated_at->format('d M Y H:i') : '-' }}</td>
                <td class="px-6 py-3 flex gap-2">
                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                       class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded">Edit</a>
                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus coupon ini?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-3 py-1 bg-rose-50 text-rose-600 rounded">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-6 text-center text-slate-400">Belum ada coupon.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $coupons->links() }}
</div>
@endsection
