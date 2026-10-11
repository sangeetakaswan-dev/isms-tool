<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskTreatmentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        // Phase 3 fields
        'risk_assessment_id',
        'action',
        'description',
        'assigned_to',
        'due_date',
        'status',
        'cost_estimate',

        // Phase 7 new fields
        'risk_id',
        'strategy',
        'completion_notes',
        'completed_at',
    ];

    protected $casts = [
        'due_date'      => 'date',
        'completed_at'  => 'datetime',
        'cost_estimate' => 'integer',
    ];

    // ─── Relationships ──────────────────────────────
    public function riskAssessment()
    {
        return $this->belongsTo(RiskAssessment::class);
    }

    public function risk()
    {
        return $this->belongsTo(Risk::class, 'risk_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Phase 3 alias (backward compat)
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}