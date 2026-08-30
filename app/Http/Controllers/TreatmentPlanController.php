<?php

namespace App\Http\Controllers;

use App\Models\RiskTreatmentPlan;
use Illuminate\Http\Request;

class TreatmentPlanController extends Controller
{
    public function index()
    {
        $treatments = RiskTreatmentPlan::whereHas('riskAssessment', function ($q) {
            $q->where('tenant_id', auth()->user()->current_tenant_id);
        })->get();
        return view('treatments.index', compact('treatments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
