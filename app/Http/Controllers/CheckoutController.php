<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use App\Models\Coupon;
use App\Models\TicketPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        $categories = \App\Models\Category::all();
        $customer = auth()->user();

        $ticketPrice = $this->getActiveTicketPrice($event);

        $activePricing = TicketPrice::where('event_id', $event->id)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        return view('checkout.create', compact(
            'event', 
            'categories', 
            'customer', 
            'ticketPrice', 
            'activePricing'
        ));
    }

    public function store(Request $request, Event $event)
    {
        if (auth()->check()) {
            $request->validate([
                'customer_phone' => 'required|string|max:20',
            ]);
            $customerName = auth()->user()->name;
            $customerEmail = auth()->user()->email;
        } else {
            $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'required|string|max:20',
            ]);
            $customerName = $request->customer_name;
            $customerEmail = $request->customer_email;
        }

        // Cegah checkout jika event belum/tidak lolos review superadmin
        if ($event->status !== Event::STATUS_APPROVED) {
            return back()->with('error', 'Event ini belum tersedia untuk dibeli.');
        }

        // Cegah checkout jika stok habis
        if ($event->stock <= 0) {
            return back()->with('error', 'Mohon maaf, tiket untuk acara ini sudah habis.');
        }

        $orderId = 'TRX-' . time() . '-' . Str::random(5);
        $ticketPrice = $this->getActiveTicketPrice($event);
        $serviceFee = 5000;
        $discount = 0;
        $couponCode = null;

        // Tangkap kupon dan hitung diskon agar sinkron
        if ($request->filled('applied_promo_code') || $request->filled('coupon_code')) {
            $couponCode = strtoupper($request->input('applied_promo_code', $request->input('coupon_code')));
            $coupon = Coupon::where('code', $couponCode)->first();

            if ($coupon && $coupon->isValid()) {
                if ($coupon->type === 'percentage') {
                    $discount = ($coupon->value / 100) * $ticketPrice;
                } else {
                    $discount = $coupon->value;
                }
                if ($discount > $ticketPrice) {
                    $discount = $ticketPrice;
                }
            }
        }

        $totalPrice = ($ticketPrice + $serviceFee) - $discount;

        // --- Reserve stok langsung saat checkout (atomic, anti race condition) ---
        $reserved = DB::transaction(function () use ($event, $orderId, $customerName, $customerEmail, $request, $totalPrice, $discount, $couponCode) {
            $lockedEvent = Event::where('id', $event->id)->lockForUpdate()->first();

            $affected = Event::where('id', $lockedEvent->id)
                ->where('stock', '>', 0)
                ->decrement('stock', 1);

            if ($affected === 0) {
                return null;
            }

            // Buat transaksi pending dengan expires_at 1 menit (sinkron dengan timer)
            return Transaction::create([
                'event_id'       => $event->id,
                'order_id'       => $orderId,
                'customer_name'  => $customerName,
                'customer_email' => $customerEmail,
                'customer_phone' => $request->customer_phone,
                'total_price'    => $totalPrice,
                'discount_amount'=> $discount,
                'coupon_code'    => $couponCode,
                'status'         => 'pending',
                'expires_at'     => now()->addMinute(1),
            ]);
        });

        if (!$reserved) {
            return back()->with('error', 'Mohon maaf, tiket untuk acara ini baru saja habis terjual.');
        }

        // --- Integrasi Midtrans ---
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totalPrice, // Nilai sudah dikurangi diskon kupon
            ],
            'customer_details' => [
                'first_name' => $customerName,
                'email' => $customerEmail,
                'phone' => $request->customer_phone,
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s T'),
                'unit' => 'minute',
                'duration' => 1, // Sinkron 1 menit dengan timer di front-end
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            Transaction::where('order_id', $orderId)->update(['snap_token' => $snapToken]);
            return redirect()->route('checkout.payment', $orderId);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function payment($order_id)
    {
        $categories = \App\Models\Category::all();
        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();
        return view('checkout.payment', compact('transaction', 'categories'));
    }

    public function success($order_id)
    {
        $categories = \App\Models\Category::all();
        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();

        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        try {
            $status = \Midtrans\Transaction::status($order_id);
            $trx_status = is_array($status) ? ($status['transaction_status'] ?? '') : ($status->transaction_status ?? '');

            if (in_array($trx_status, ['settlement', 'capture'])) {
                if ($transaction->status === 'pending') {
                    $transaction->update(['status' => 'success']);
                    try {
                        \Illuminate\Support\Facades\Mail::to($transaction->customer_email)
                            ->send(new \App\Mail\EventTicketMail($transaction));
                    } catch (\Exception $e) {
                        \Log::error('Gagal mengirim email E-Ticket: ' . $e->getMessage());
                    }
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan atau gagal diproses.');
        }

        return view('checkout.success', compact('transaction', 'categories'));
    }

    public function applyCoupon(Request $request, $eventId)
    {
        $event = \App\Models\Event::findOrFail($eventId);
        $ticketPrice = $this->getActiveTicketPrice($event);

        $code = strtoupper($request->input('coupon_code'));
        $coupon = \App\Models\Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Kode kupon "' . $code . '" tidak valid.'
            ]);
        }

        $discount = $coupon->type === 'percentage'
            ? ($ticketPrice * $coupon->value) / 100
            : $coupon->value;

        if ($discount > $ticketPrice) {
            $discount = $ticketPrice;
        }

        $newTotal = ($ticketPrice + 5000) - $discount;

        return response()->json([
            'success' => true,
            'message' => 'Kupon berhasil digunakan!',
            'discount' => $discount,
            'new_total' => $newTotal
        ]);
    }

    private function getActiveTicketPrice(Event $event): float
    {
        $activePrice = TicketPrice::where('event_id', $event->id)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        return $activePrice ? $activePrice->price : $event->price;
    }

    public function releaseExpiredReservations()
    {
        $expired = Transaction::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($expired as $trx) {
            DB::transaction(function () use ($trx) {
                $trx->event->increment('stock', 1);
                $trx->update(['status' => 'cancelled']);
            });
        }
    }


}