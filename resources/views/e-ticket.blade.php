<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { 
            background-color: #4f46e5; 
        }
        
        /* Optimasi Khusus Cetak PDF / Printer */
        @media print {
            body { 
                background-color: #ffffff !important; 
            }
            .no-print { 
                display: none !important; 
            }
            .print-shadow-none { 
                box-shadow: none !important; 
                border: none !important; 
            }
            @page {
                margin: 0;
            }
            body {
                padding: 1.5cm;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-6 antialiased font-sans">

    <div class="max-w-md w-full">
        @if($transactions->count())
            @foreach($transactions as $transaction)
            
            <div class="text-center mb-8 text-white no-print">
                <!-- Menghapus border dan mengubah bg menjadi bg-green-100 agar pas dengan gambar contoh -->
<div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
    <svg class="animate-bounce w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
    </svg>
</div>
                <h1 class="text-3xl font-black tracking-tight">Pembayaran Berhasil!</h1>
                <p class="text-indigo-100 mt-2">Karcis e-ticket Anda siap digunakan.</p>
            </div>

            <div class="bg-white text-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl relative print-shadow-none">
                
                <div class="p-8 bg-indigo-50/75 border-b-4 border-dashed border-slate-200 text-center relative">
                    <p class="text-indigo-600 font-extrabold uppercase tracking-widest text-xs mb-2">E-Ticket Resmi</p>
                    <h2 class="text-2xl font-black leading-tight text-slate-900">
                        {{ optional($transaction->event)->title ?? 'Detail Acara' }}
                    </h2>
                    
                    <div class="absolute -left-4 -bottom-4 w-8 h-8 bg-indigo-600 rounded-full z-10"></div>
                    <div class="absolute -right-4 -bottom-4 w-8 h-8 bg-indigo-600 rounded-full z-10"></div>
                </div>

                <div class="p-8 space-y-6">
                    <div class="flex flex-col items-center justify-center py-6 bg-slate-50 rounded-2xl border border-slate-100">
    <!-- Kotak Putih Wadah QR -->
    <div class="w-32 h-32 bg-white p-3 rounded-xl shadow-sm border border-slate-200 flex items-center justify-center">
        <!-- Ukuran disesuaikan menjadi 100 agar pas dengan padding dalam w-32 (128px) -->
        <div class="opacity-90">
            {!! QrCode::size(100)->margin(0)->generate($transaction->order_id) !!}
        </div>
    </div>
    <!-- Order ID di bawah QR -->
    <p class="mt-4 font-mono font-black text-lg tracking-widest text-indigo-600">{{ $transaction->order_id }}</p>
    <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">Tunjukkan QR Code ini di pintu masuk event</p>
</div>


                    <div class="grid grid-cols-2 gap-y-4 gap-x-6 text-sm">
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Nama Penonton</p>
                            <p class="font-extrabold text-slate-900 text-base mt-0.5">{{ $transaction->customer_name }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Status Tiket</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 mt-1">
                                {{ strtoupper($transaction->status) }}
                            </span>
                        </div>
                        <div class="col-span-2 border-t border-slate-100 pt-3">
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Tanggal & Waktu</p>
                            <p class="font-bold text-slate-800 mt-0.5">
                                {{ optional($transaction->event)->date?->format('d M Y — H:i') ?? 'Segera Dikonfirmasi' }} WIB
                            </p>
                        </div>
                        <div class="col-span-2 border-t border-slate-100 pt-3">
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Lokasi / Venue</p>
                            <p class="font-bold text-slate-800 mt-0.5">
                                {{ optional($transaction->event)->location ?? 'Online / TBD' }}
                            </p>
                        </div>
                    </div>

                    <div class="border-t-2 border-dashed border-slate-200 pt-4 flex justify-between items-center">
                        <div>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Bayar</p>
                            <p class="text-xs text-slate-400">Metode: Midtrans/E-Wallet</p>
                        </div>
                        <p class="text-xl font-black text-indigo-600">
                            Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <div class="px-8 pb-8 space-y-3 no-print">
                    <button onclick="window.print()" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg hover:bg-indigo-700 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Cetak / Simpan PDF
                    </button>
                    <a href="{{ route('ticket') }}" class="block text-center py-2 text-sm text-slate-500 font-bold hover:text-indigo-600 transition">
                        Kembali ke Tiket Saya
                    </a>
                </div>
            </div>
            @endforeach
        @else
            <div class="bg-white rounded-[2.5rem] p-8 text-center shadow-2xl">
                <p class="text-slate-500 font-medium">Data karcis tidak ditemukan.</p>
                <a href="{{ route('ticket') }}" class="mt-4 inline-block px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition">
                    Kembali ke Daftar Tiket
                </a>
            </div>
        @endif
    </div>

</body>
</html>