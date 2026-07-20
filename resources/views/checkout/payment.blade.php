@extends('layouts.app')

@section('title', 'Pembayaran - ' . $transaction->event->title)

@section('content')
<main class="max-w-4xl mx-auto px-6 py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        {{-- CARD 1: Ringkasan Pesanan (Sinkron dengan Data Transaksi DB) --}}
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm h-fit">
            <h3 class="text-xl font-bold mb-6 border-b pb-4 text-slate-800">Pesanan Anda</h3>
            <div class="flex gap-4 items-start">
                <img src="{{ ($transaction->event->poster_path && Storage::disk('public')->exists($transaction->event->poster_path)) ? asset('storage/' . $transaction->event->poster_path) : 'https://placehold.co/200x200' }}"
                    alt="Event" class="w-20 h-20 rounded-2xl object-cover shrink-0">
                <div class="min-w-0">
                    <h4 class="font-extrabold text-base text-slate-800 truncate">{{ $transaction->event->title }}</h4>
                    <p class="text-xs text-slate-500 mt-1">{{ \Carbon\Carbon::parse($transaction->event->date)->format('d M Y') }} • {{ $transaction->event->location }}</p>
                    <p class="text-indigo-600 font-bold text-sm mt-2">
                        {{ $transaction->quantity ?? 1 }} x Rp {{ number_format(($transaction->unit_price ?? $transaction->total_price), 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t space-y-3 text-sm">
                <div class="flex justify-between text-slate-500">
                    <span>Harga Tiket {{ $transaction->ticket_category ? '(' . $transaction->ticket_category . ')' : '' }}</span>
                    <span>Rp {{ number_format(($transaction->subtotal ?? $transaction->total_price), 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Biaya Layanan</span>
                    <span>Rp {{ number_format($transaction->admin_fee ?? 5000, 0, ',', '.') }}</span>
                </div>

                @if(isset($transaction->discount_amount) && $transaction->discount_amount > 0)
                <div class="flex justify-between text-emerald-600 font-bold">
                    <span>Potongan Kupon ({{ $transaction->coupon_code }})</span>
                    <span>-Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                </div>
                @endif

                <div class="flex justify-between text-xl font-black mt-4 pt-4 border-t text-slate-800">
                    <span>Total Bayar</span>
                    <span class="text-indigo-600">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- CARD 2: Tombol Bayar & Timer 1 Menit --}}
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm text-center flex flex-col justify-between">
            <div>
                <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                
                <h2 class="text-2xl font-black mb-2 text-slate-800">Selesaikan Pembayaran</h2>
                <p class="text-sm text-slate-500 mb-6">Batas waktu pembayaran Anda:</p>
                
                {{-- Tampilan Timer 1 Menit --}}
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl inline-block w-full">
                    <span id="countdown" class="text-3xl font-black text-rose-600">01:00</span>
                </div>

                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 mb-6 text-left">
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Total Tagihan</p>
                    <h3 class="text-2xl font-extrabold text-indigo-600">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</h3>
                    <p class="text-[10px] text-slate-400 mt-1">Order ID: {{ $transaction->order_id }}</p>
                </div>
            </div>

            <button id="pay-button" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-base shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition">
                Bayar Sekarang
            </button>
        </div>

    </div>
</main>

{{-- Script Midtrans Snap & Timer 1 Menit --}}
@if($transaction->snap_token)
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    // Timer 1 Menit (60 Detik)
    let timeLeft = 60;
    const countdownElement = document.getElementById('countdown');
    
    const timerInterval = setInterval(function() {
        let minutes = Math.floor(timeLeft / 60);
        let seconds = timeLeft % 60;

        countdownElement.innerHTML = 
            String(minutes).padStart(2, '0') + ":" + 
            String(seconds).padStart(2, '0');

        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            alert("Waktu pembayaran telah habis!");
            // Langsung arahkan kembali ke halaman detail event / halaman sebelumnya (menghilangkan tampilan Midtrans)
            window.location.href = "{{ route('events.show', $transaction->event_id) }}";
        }
        timeLeft--;
    }, 1000);

    document.getElementById('pay-button').onclick = function () {
        snap.pay('{{ $transaction->snap_token }}', {
            onSuccess: function(result){
                window.location.href = "{{ route('checkout.success', $transaction->order_id) }}";
            },
            onPending: function(result){
                window.location.href = "{{ route('checkout.success', $transaction->order_id) }}";
            },
            onError: function(result){
                alert("Pembayaran Gagal!");
                window.location.href = "{{ route('events.show', $transaction->event_id) }}";
            }
        });
    };

    // Otomatis memicu pop-up Midtrans saat halaman pertama kali dibuka
    window.onload = function() {
        const payBtn = document.getElementById('pay-button');
        if(payBtn) {
            payBtn.click();
        }
    }
</script>
@endif
@endsection