<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');

        $organizations = Organization::with('owner')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10);

        return view('admin.organizations.index', compact('organizations', 'status'));
    }

    public function approve(Organization $organization)
    {
        $organization->update(['status' => Organization::STATUS_APPROVED]);

        return back()->with('success', 'Organisasi "' . $organization->name . '" disetujui.');
    }

    public function suspend(Organization $organization)
    {
        $organization->update(['status' => Organization::STATUS_SUSPENDED]);

        return back()->with('success', 'Organisasi "' . $organization->name . '" dibekukan.');
    }
}
