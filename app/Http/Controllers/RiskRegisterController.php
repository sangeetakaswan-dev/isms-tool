<?php

namespace App\Http\Controllers;

use App\DTOs\RiskData;
use App\Http\Requests\Risk\StoreRiskRequest;
use App\Http\Requests\Risk\UpdateRiskRequest;
use App\Models\Asset;
use App\Models\Risk;
use App\Models\User;
use App\Services\Risk\RiskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RiskRegisterController extends Controller
{
    public function __construct(
        private readonly RiskService $riskService,
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Risk::class);

        $risks = $this->riskService->all();

        // Filters
        if ($level = $request->query('level')) {
            $risks = $risks->where('risk_level', $level);
        }
        if ($status = $request->query('status')) {
            $risks = $risks->where('status', $status);
        }

        $summary = $this->riskService->summary();

        return view('risks.index', compact('risks', 'summary'));
    }

    public function create()
    {
        $this->authorize('create', Risk::class);

        $assets = Asset::orderBy('name')->get();
        $users  = User::whereHas('tenants', function ($q) {
            $q->where('tenants.id', auth()->user()->current_tenant_id);
        })->get();

        return view('risks.create', compact('assets', 'users'));
    }

    public function store(StoreRiskRequest $request)
    {
        $this->authorize('create', Risk::class);

        $data = RiskData::fromRequest(
            $request->validated(),
            auth()->user()->current_tenant_id
        );

        $risk = $this->riskService->create($data);

        return redirect()
            ->route('risks.show', $risk)
            ->with('success', 'Risk created successfully.');
    }

    public function show(Risk $risk)
    {
        $this->authorize('view', $risk);

        $risk->load(['asset', 'owner', 'treatmentPlans.assignedUser']);

        return view('risks.show', compact('risk'));
    }

    public function edit(Risk $risk)
    {
        $this->authorize('update', $risk);

        $assets = Asset::orderBy('name')->get();
        $users  = User::whereHas('tenants', function ($q) {
            $q->where('tenants.id', auth()->user()->current_tenant_id);
        })->get();

        return view('risks.edit', compact('risk', 'assets', 'users'));
    }

    public function update(UpdateRiskRequest $request, Risk $risk)
    {
        $this->authorize('update', $risk);

        $merged = array_merge([
            'title'         => $risk->title,
            'description'   => $risk->description,
            'category'      => $risk->category,
            'likelihood'    => $risk->likelihood,
            'impact'        => $risk->impact,
            'status'        => $risk->status,
            'asset_id'      => $risk->asset_id,
            'owner_id'      => $risk->owner_id,
            'identified_at' => $risk->identified_at?->toDateString(),
        ], $request->validated());

        $data = RiskData::fromRequest($merged, $risk->tenant_id);
        $this->riskService->update($risk, $data);

        return redirect()
            ->route('risks.show', $risk)
            ->with('success', 'Risk updated successfully.');
    }

    public function destroy(Risk $risk)
    {
        $this->authorize('delete', $risk);

        $this->riskService->delete($risk);

        return redirect()
            ->route('risks.index')
            ->with('success', 'Risk deleted successfully.');
    }

    /**
     * JSON endpoint for heatmap grid.
     */
    public function heatmapData(): JsonResponse
    {
        $this->authorize('viewAny', Risk::class);

        return response()->json([
            'grid'    => $this->riskService->heatmap(),
            'summary' => $this->riskService->summary(),
            'top'     => $this->riskService->topRisks(10),
        ]);
    }
}