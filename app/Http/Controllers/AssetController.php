<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::where('tenant_id', auth()->user()->current_tenant_id)->get();
        return view('assets.index', compact('assets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ✅ Security: Sirf allowed roles hi create kar sakte hain
        $this->authorize('create', Asset::class);

        return view('assets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ✅ Security Check
        $this->authorize('create', Asset::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'asset_type' => 'required|in:hardware,software,data,people,facility,service',
            'confidentiality_rating' => 'required|integer|min:1|max:5',
            'integrity_rating' => 'required|integer|min:1|max:5',
            'availability_rating' => 'required|integer|min:1|max:5',
            'description' => 'nullable|string',
        ]);

        $asset = Asset::create(array_merge($validated, [
            'tenant_id' => auth()->user()->current_tenant_id,
        ]));

        return redirect()->route('assets.index')->with('success', 'Asset created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        // ✅ Security: Check karo ki user isi tenant ka hai
        $this->authorize('view', $asset);

        return view('assets.show', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
    {
        // ✅ Security Check
        $this->authorize('update', $asset);

        return view('assets.edit', compact('asset'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        // ✅ Security Check
        $this->authorize('update', $asset);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'asset_type' => 'required|in:hardware,software,data,people,facility,service',
            'confidentiality_rating' => 'required|integer|min:1|max:5',
            'integrity_rating' => 'required|integer|min:1|max:5',
            'availability_rating' => 'required|integer|min:1|max:5',
            'description' => 'nullable|string',
        ]);

        $asset->update($validated);

        return redirect()->route('assets.index')->with('success', 'Asset updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        // ✅ Security Check: Sirf Owner/Admin delete kar sakte hain
        $this->authorize('delete', $asset);

        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully.');
    }
}