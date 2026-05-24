<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    /**
     * Tampilkan daftar partner
     */
    public function index()
    {
        $partners = Partner::with('category')->latest()->paginate(10);
        return view('admin.partners.index', compact('partners'));
    }

    /**
     * Form tambah partner
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.partners.create', compact('categories'));
    }

    /**
     * Simpan partner baru
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('partners', 'public');
        }

        Partner::create($data);

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partner berhasil ditambahkan!');
    }

    /**
     * Form edit partner
     */
    public function edit(Partner $partner)
    {
        $categories = Category::all();
        return view('admin.partners.edit', compact('partner', 'categories'));
    }

    /**
     * Update partner
     */
    public function update(Request $request, Partner $partner)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        if ($request->hasFile('logo')) {
            // hapus logo lama jika ada
            if ($partner->logo) {
                Storage::disk('public')->delete($partner->logo);
            }
            $data['logo'] = $request->file('logo')->store('partners', 'public');
        }

        $partner->update($data);

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partner berhasil diperbarui!');
    }

    /**
     * Hapus partner
     */
    public function destroy(Partner $partner)
    {
        if ($partner->logo) {
            Storage::disk('public')->delete($partner->logo);
        }
        $partner->delete();

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partner berhasil dihapus!');
    }
}
