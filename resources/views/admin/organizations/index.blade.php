@extends('layouts.admin')

@section('title', 'Kelola Organisasi')
@section('page_title', 'Kelola Organisasi (Kepanitiaan/HIMA)')

@section('content')

@if (session('success'))
    <div class="mb-6 p-4 rounded-xl bg-green-50 text-green-700 text-sm font-semibold">{{ session('success') }}</div>
@endif

<div class="flex gap-2 mb-6">
    <a href="{{ route('admin.organizations.index') }}" class="px-4 py-2 rounded-xl text-sm font-bold {{ !$status ? 'bg-indigo-900 text-white' : 'bg-white border border-slate-200' }}">Semua</a>
    <a href="{{ route('admin.organizations.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-sm font-bold {{ $status === 'pending' ? 'bg-indigo-900 text-white' : 'bg-white border border-slate-200' }}">Menunggu</a>
    <a href="{{ route('admin.organizations.index', ['status' => 'approved']) }}" class="px-4 py-2 rounded-xl text-sm font-bold {{ $status === 'approved' ? 'bg-indigo-900 text-white' : 'bg-white border border-slate-200' }}">Disetujui</a>
    <a href="{{ route('admin.organizations.index', ['status' => 'suspended']) }}" class="px-4 py-2 rounded-xl text-sm font-bold {{ $status === 'suspended' ? 'bg-indigo-900 text-white' : 'bg-white border border-slate-200' }}">Dibekukan</a>
</div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500 text-left">
            <tr>
                <th class="px-6 py-3">Organisasi</th>
                <th class="px-6 py-3">Penanggung Jawab</th>
                <th class="px-6 py-3">Kontak</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($organizations as $org)
                <tr class="border-t border-slate-100">
                    <td class="px-6 py-3 font-semibold">{{ $org->name }}</td>
                    <td class="px-6 py-3">{{ $org->owner->name ?? '-' }}</td>
                    <td class="px-6 py-3">{{ $org->contact_email }}<br><span class="text-slate-400">{{ $org->contact_phone }}</span></td>
                    <td class="px-6 py-3">
                        @if ($org->status === 'approved')
                            <span class="px-2 py-1 rounded-full bg-green-50 text-green-600 text-xs font-bold">Disetujui</span>
                        @elseif ($org->status === 'pending')
                            <span class="px-2 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-bold">Menunggu</span>
                        @else
                            <span class="px-2 py-1 rounded-full bg-red-50 text-red-600 text-xs font-bold">Dibekukan</span>
                        @endif
                    </td>
                    <td class="px-6 py-3 space-x-2">
                        @if ($org->status !== 'approved')
                            <form method="POST" action="{{ route('admin.organizations.approve', $org) }}" class="inline">
                                @csrf
                                <button class="text-green-600 font-bold hover:underline">Setujui</button>
                            </form>
                        @endif
                        @if ($org->status !== 'suspended')
                            <form method="POST" action="{{ route('admin.organizations.suspend', $org) }}" class="inline" onsubmit="return confirm('Bekukan organisasi ini?')">
                                @csrf
                                <button class="text-red-600 font-bold hover:underline">Bekukan</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada organisasi.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">{{ $organizations->links() }}</div>
@endsection
