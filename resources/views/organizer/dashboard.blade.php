@extends('layouts.organizer')

@section('title', 'Dashboard Organizer')
@section('page_title', 'Dashboard ' . $organization->name)

@section('content')

@if ($organization->isPending())
    <div class="mb-8 p-6 bg-amber-50 border border-amber-200 rounded-2xl">
        <p class="font-bold text-amber-700">Organisasi Anda sedang menunggu verifikasi Superadmin.</p>
        <p class="text-amber-600 text-sm mt-1">Anda belum bisa membuat event sampai pengajuan ini disetujui.</p>
    </div>
@elseif ($organization->isSuspended())
    <div class="mb-8 p-6 bg-red-50 border border-red-200 rounded-2xl">
        <p class="font-bold text-red-700">Organisasi Anda dibekukan oleh Superadmin.</p>
        <p class="text-red-600 text-sm mt-1">Hubungi pihak AmikomEventHub untuk informasi lebih lanjut.</p>
    </div>
@endif

<!-- Kartu Statistik Ringkas -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Total Pendapatan</p>
        <h3 class="text-2xl font-black">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
    </div>
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Tiket Terjual</p>
        <h3 class="text-2xl font-black">{{ number_format($ticketsSold, 0, ',', '.') }}</h3>
    </div>
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Event Menunggu Review</p>
        <h3 class="text-2xl font-black">{{ $pendingReview }}</h3>
    </div>
</div>

<!-- Bagian Grafik Pertumbuhan Penyelenggaraan Event -->
<div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm mb-10">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-black text-slate-900">Grafik Pertumbuhan Event</h2>
            <p class="text-xs text-slate-500 mt-0.5">Statistik jumlah event yang diselenggarakan per bulan pada tahun ini</p>
        </div>
    </div>
    <div class="relative h-72 w-full">
        <canvas id="eventGrowthChart"></canvas>
    </div>
</div>

<!-- Tabel Event Terbaru -->
<div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-black">Event Terbaru</h2>
    @if ($organization->isApproved())
        <a href="{{ route('organizer.events.create') }}" class="px-5 py-2 bg-emerald-600 text-white rounded-xl font-bold text-sm hover:bg-emerald-700">
            + Buat Event
        </a>
    @endif
</div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500 text-left">
            <tr>
                <th class="px-6 py-3">Judul</th>
                <th class="px-6 py-3">Tanggal</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($events->take(5) as $event)
                <tr class="border-t border-slate-100">
                    <td class="px-6 py-3 font-semibold">{{ $event->title }}</td>
                    <td class="px-6 py-3">{{ $event->date->format('d M Y') }}</td>
                    <td class="px-6 py-3">
                        @if ($event->status === 'approved')
                            <span class="px-2 py-1 rounded-full bg-green-50 text-green-600 text-xs font-bold">Tayang</span>
                        @elseif ($event->status === 'pending')
                            <span class="px-2 py-1 rounded-full bg-amber-50 text-amber-600 text-xs font-bold">Menunggu Review</span>
                        @else
                            <span class="px-2 py-1 rounded-full bg-red-50 text-red-600 text-xs font-bold">Ditolak</span>
                        @endif
                    </td>
                    <td class="px-6 py-3">{{ $event->stock }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada event.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('eventGrowthChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Event Dibuat',
                    data: @json($chartData),
                    borderColor: '#059669', // Warna hijau emerald agar senada
                    backgroundColor: 'rgba(5, 150, 105, 0.05)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointBackgroundColor: '#059669'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            color: '#f8fafc'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endsection