<?php

namespace App\Http\Controllers;

use App\Models\Control;
use App\Models\Domain;
use App\Models\Assessment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $tenant = auth()->user()->currentTenant;
        
        $stats = [
            'total_domains' => Domain::count(),
            'total_controls' => Control::active()->count(),
            'annex_a_controls' => Control::active()->annexA()->count(),
            'main_clause_controls' => Control::active()->mainClause()->count(),
            'active_assessments' => Assessment::active()->count(),
            'completed_assessments' => Assessment::byStatus('completed')->count(),
        ];

        $domains = Domain::ordered()
            ->withCount(['controls' => function ($query) {
                $query->active();
            }])
            ->get();

        $recentAssessments = Assessment::with('leadAssessor')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'domains', 'recentAssessments'));
    }
}