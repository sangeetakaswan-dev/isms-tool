<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RiskAssessment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'risk_assessments';

    protected $fillable = [
        'tenant_id',
        'asset_id',
        'threat',
        'vulnerability',
        'likelihood',
        'impact',
        'risk_score',
        'risk_level',
        'treatment',
        'treatment_description',
        'residual_likelihood',
        'residual_impact',
        'residual_score',
        'residual_level',
        'risk_owner',
        'status',
        'review_date',
    ];

    protected $casts = [
        'likelihood' => 'integer',
        'impact' => 'integer',
        'risk_score' => 'integer',
        'review_date' => 'date',
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'risk_owner');
    }

    public function treatmentPlans()
    {
        return $this->hasMany(RiskTreatmentPlan::class);
    }

    // Scopes
    public function scopeForTenant($query)
    {
        return $query->where('tenant_id', auth()->user()->current_tenant_id);
    }
}