<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Menampilkan daftar kupon dengan fitur pencarian
     */
    public function index(Request $request)
    {
        // 1. Tangkap kata kunci pencarian dari URL
        $search = $request->query('search');

        // 2. Filter data berdasarkan input pencarian jika ada
        $coupons = Coupon::query()
            ->when($search, function ($query, $search) {
                return $query->where('code', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString(); // Memastikan parameter search tidak hilang saat pindah halaman pagination

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code|string|max:50',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:1',
            'expired_at' => 'nullable|date|after_or_equal:today',
        ]);

        Coupon::create([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'expired_at' => $request->expired_at,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Kupon baru berhasil disimpan!');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:1',
            'expired_at' => 'nullable|date|after_or_equal:today',
        ]);

        $coupon->update([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'expired_at' => $request->expired_at,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Data kupon berhasil diperbarui!');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Kupon berhasil dihapus!');
    }
}