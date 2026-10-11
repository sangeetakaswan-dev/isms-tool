<?php

namespace App\Http\Controllers;

use App\DTOs\RiskTreatmentData;
use App\Http\Requests\Risk\StoreTreatmentRequest;
use App\Models\Risk;
use App\Models\RiskTreatmentPlan;
use App\Models\User;
use App\Services\Risk\RiskTreatmentService;
use Illuminate\Http\Request;

class RiskTreatmentController extends Controller
{
    public function __construct(
        private readonly RiskTreatmentService $treatmentService,
    ) {
    }

    public function create(Risk $risk)
    {
        $this->authorize('update', $risk);

        $users = User::whereHas('tenants', function ($q) {
            $q->where('tenants.id', auth()->user()->current_tenant_id);
        })->get();

        return view('treatments.form', [
            'risk' => $risk,
            'users' => $users,
            'plan' => null,
        ]);
    }

    public function store(StoreTreatmentRequest $request, Risk $risk)
    {
        $this->authorize('update', $risk);

        $data = RiskTreatmentData::fromRequest($request->validated(), $risk->id);
        $this->treatmentService->create($data);

        return redirect()
            ->route('risks.show', $risk)
            ->with('success', 'Treatment plan created.');
    }

    public function edit(RiskTreatmentPlan $plan)
    {
        $risk = $plan->risk;
        $this->authorize('update', $risk);

        $users = User::whereHas('tenants', function ($q) {
            $q->where('tenants.id', auth()->user()->current_tenant_id);
        })->get();

        return view('treatments.form', [
            'risk' => $risk,
            'plan' => $plan,
            'users' => $users,
        ]);
    }

    public function update(StoreTreatmentRequest $request, RiskTreatmentPlan $plan)
    {
        $this->authorize('update', $plan->risk);

        $plan->update($request->validated());

        return redirect()
            ->route('risks.show', $plan->risk_id)
            ->with('success', 'Treatment plan updated.');
    }

    public function complete(Request $request, RiskTreatmentPlan $plan)
    {
        $this->authorize('update', $plan->risk);

        $request->validate(['completion_notes' => ['nullable', 'string', 'max:2000']]);

        $this->treatmentService->complete($plan, $request->input('completion_notes'));

        return redirect()
            ->route('risks.show', $plan->risk_id)
            ->with('success', 'Treatment plan marked complete.');
    }

    public function destroy(RiskTreatmentPlan $plan)
    {
        $this->authorize('update', $plan->risk);

        $riskId = $plan->risk_id;
        $this->treatmentService->delete($plan);

        return redirect()
            ->route('risks.show', $riskId)
            ->with('success', 'Treatment plan deleted.');
    }
}