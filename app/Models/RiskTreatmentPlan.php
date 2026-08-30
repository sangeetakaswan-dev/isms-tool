<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskTreatmentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'risk_assessment_id',
        'action',
        'description',
        'assigned_to',
        'due_date',
        'status',
        'cost_estimate',
    ];

    protected $casts = [
        'due_date' => 'date',
        'cost_estimate' => 'integer',
    ];

    // Relationships
    public function riskAssessment()
    {
        return $this->belongsTo(RiskAssessment::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}