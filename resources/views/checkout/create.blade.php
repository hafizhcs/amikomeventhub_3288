@extends('layouts.app')

@section('title', 'Checkout - ' . $event->title)

@section('content')
    <main class="max-w-3xl mx-auto px-6 py-20">
        {{-- Pesan Error jika ada --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-800 rounded-r-lg shadow-sm">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-8">
            <!-- CARD 1: Ringkasan Pesanan -->
            <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                <h3 class="text-xl font-bold mb-6 border-b pb-4 text-slate-800">Pesanan Anda</h3>
                <div class="flex gap-6 items-start">
                    <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) ? asset('storage/' . $event->poster_path) : 'https://placehold.co/200x200' }}"
                        alt="Event" class="w-24 h-24 rounded-2xl object-cover">
                    <div>
                        <h4 class="font-extrabold text-lg text-slate-800">{{ $event->title }}</h4>
                        <p class="text-slate-500">{{ $event->date->format('d M Y') }} • {{ $event->location }}</p>
                        <p class="text-indigo-600 font-bold mt-2">1 x Rp
                            {{ number_format($event->current_price, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t space-y-3">
                    <div class="flex justify-between text-slate-500">
                        <span>Harga Tiket {{ $activePricing?->category ? '(' . $activePricing->category . ')' : '' }}</span>
                        <span>Rp {{ number_format($ticketPrice, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Biaya Layanan</span>
                        <span>Rp 5.000</span>
                    </div>


                    {{-- Tampilan Potongan Diskon Kupon (Tersembunyi secara default) --}}
                    <div id="coupon_discount_row" class="flex justify-between text-emerald-600 font-bold hidden text-sm">
                        <span>Potongan Kupon (<span id="display_coupon_code"></span>)</span>
                        <span>-Rp <span id="display_discount_amount">0</span></span>
                    </div>

                    <div class="flex justify-between text-2xl font-black mt-4 pt-4 border-t text-slate-800">
                        <span>Total Bayar</span>
                        <span id="total_payment" class="text-indigo-600">
                            Rp {{ number_format(($ticketPrice + 5000), 0, ',', '.') }}
                        </span>

                    </div>
                </div>
            </div>

            <!-- CARD 2: Form Data Pemesan & Input Kupon -->
            <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                <form action="{{ route('checkout.store', $event->id) }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Hidden input untuk mengirimkan kode kupon yang valid saat form utama disubmit --}}
                    <input type="hidden" name="coupon_code" id="applied_coupon_code">

                    <div class="flex items-center gap-2 mb-6 text-slate-800">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        <h3 class="text-xl font-bold">Data Pemesan</h3>
                    </div>

                    @auth
                        <div
                            class="rounded-2xl bg-indigo-50/60 p-4 border border-indigo-100 flex items-start gap-3 shadow-sm my-4">
                            <div class="flex items-center h-5 mt-0.5">
                                <input id="use_account_data" type="checkbox" checked
                                    class="w-5 h-5 text-indigo-600 border-indigo-200 rounded-lg focus:ring-indigo-500 bg-white transition cursor-pointer">
                            </div>
                            <div class="min-w-0">
                                <label for="use_account_data"
                                    class="font-bold text-slate-800 text-sm block cursor-pointer select-none">
                                    Gunakan data akun saya
                                </label>
                                <p class="text-xs text-slate-500 font-medium mt-0.5">
                                    Otomatis isi nama & email dari akun Anda (<span
                                        class="font-bold text-slate-600">{{ auth()->user()->email }}</span>).
                                </p>
                            </div>
                        </div>
                    @endauth

                    {{-- Input Data Diri --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama
                            Lengkap</label>
                        <input type="text" id="customer_name" name="customer_name" required
                            value="{{ auth()->check() ? auth()->user()->name : old('customer_name') }}"
                            class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:border-indigo-600 outline-none transition font-medium">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Email
                                Aktif</label>
                            <input type="email" id="customer_email" name="customer_email" required
                                value="{{ auth()->check() ? auth()->user()->email : old('customer_email') }}"
                                class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:border-indigo-600 outline-none transition font-medium">
                            <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-tighter">*E-Ticket akan
                                dikirim ke email ini</p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">No.
                                WhatsApp</label>
                            <input type="tel" name="customer_phone" required value="{{ old('customer_phone') }}"
                                class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:border-indigo-600 outline-none transition font-medium">
                        </div>
                    </div>

                    {{-- Kotak Input Kode Kupon --}}
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 mt-6">
                        <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">Punya Kode
                            Kupon?</label>
                        <div class="flex gap-3">
                            <input type="text" id="coupon_code_input" placeholder="MASUKKAN KODE KUPON"
                                class="flex-1 px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-indigo-600 outline-none text-xs font-bold uppercase tracking-wide placeholder-slate-400 shadow-sm">
                            <button type="button" id="btn_apply_coupon"
                                class="px-5 py-3 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold tracking-wide transition-colors shrink-0 shadow-sm">
                                Terapkan
                            </button>
                        </div>
                        <div id="coupon_message" class="text-xs font-bold mt-2 hidden"></div>
                    </div>

                    <button type="submit"
                        class="w-full py-5 bg-indigo-600 text-white rounded-2xl font-black text-xl shadow-xl shadow-indigo-200 hover:bg-indigo-700 active:scale-95 transition-all">
                        Lanjut Pembayaran
                    </button>
                    <p class="text-center text-xs text-slate-400">Dengan menekan tombol di atas, Anda menyetujui Syarat &
                        Ketentuan kami.</p>
                </form>
            </div>
        </div>
    </main>

    {{-- JAVASCRIPT LOGIC AUTOFILL + COUPON CODE AJAX --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- 1. Logika Autofill Data Akun ---
            @auth
                        const checkbox = document.getElementById('use_account_data');
                const nameInput = document.getElementById('customer_name');
                const emailInput = document.getElementById('customer_email');
                const authName = "{{ auth()->user()->name }}";
                const authEmail = "{{ auth()->user()->email }}";

                if (checkbox) {
                    checkbox.addEventListener('change', function () {
                        if (this.checked) {
                            nameInput.value = authName;
                            emailInput.value = authEmail;
                        } else {
                            nameInput.value = '';
                            emailInput.value = '';
                        }
                    });
                }
            @endauth

                // --- 2. Logika AJAX Kupon (Coupon) ---
                const btnApplyCoupon = document.getElementById('btn_apply_coupon');
            const couponInput = document.getElementById('coupon_code_input');
            const couponMessage = document.getElementById('coupon_message');
            const appliedCouponInput = document.getElementById('applied_coupon_code');

            const couponDiscountRow = document.getElementById('coupon_discount_row');
            const displayCouponCode = document.getElementById('display_coupon_code');
            const displayDiscountAmount = document.getElementById('display_discount_amount');
            const totalPayment = document.getElementById('total_payment');

            const basePrice = {{ $event->price }};
            const serviceFee = 5000;

            btnApplyCoupon.addEventListener('click', function () {
                const code = couponInput.value.trim();
                if (!code) return;

                // Lakukan request ke server tanpa reload
                fetch("{{ route('checkout.applyCoupon', $event->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ coupon_code: code })
                })
                    .then(res => res.json())
                    .then(data => {
                        couponMessage.classList.remove('hidden');
                        if (data.success) {
                            // Update Pesan Sukses
                            couponMessage.textContent = data.message;
                            couponMessage.className = "text-xs font-bold mt-2 text-emerald-600 block";

                            // Kunci data ke input hidden untuk dikirim saat submit form utama
                            appliedCouponInput.value = code;

                            // Update Rincian Harga di UI
                            displayCouponCode.textContent = code.toUpperCase();
                            displayDiscountAmount.textContent = new Intl.NumberFormat('id-ID').format(data.discount);
                            couponDiscountRow.classList.remove('hidden');

                            // Hitung Total Baru
                            totalPayment.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.new_total);
                        } else {
                            // Tampilkan Pesan Gagal
                            couponMessage.textContent = data.message;
                            couponMessage.className = "text-xs font-bold mt-2 text-rose-600 block";

                            // Reset Status
                            appliedCouponInput.value = '';
                            couponDiscountRow.classList.add('hidden');
                            totalPayment.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(basePrice + serviceFee);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Terjadi kendala sistem saat memvalidasi kode kupon.');
                    });
            });
        });
    </script>
@endsection