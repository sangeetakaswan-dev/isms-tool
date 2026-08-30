<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\TenantScope;

class Assessment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'scope',
        'start_date',
        'target_date',
        'completed_date',
        'status',
        'progress_percentage',
        'lead_assessor_id',
        'team_members',
    ];

    protected $casts = [
        'start_date' => 'date',
        'target_date' => 'date',
        'completed_date' => 'date',
        'team_members' => 'array',
        'progress_percentage' => 'integer',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new TenantScope());
    }

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function leadAssessor()
    {
        return $this->belongsTo(User::class, 'lead_assessor_id');
    }

    public function responses()
    {
        return $this->hasMany(AssessmentResponse::class);
    }

    public function teamMembers()
    {
        return $this->belongsToMany(User::class, 'assessment_team')
            ->withPivot('role')
            ->withTimestamps();
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['draft', 'in_progress']);
    }

    // Helper Methods
    public function getComplianceScoreAttribute(): float
    {
        $compliantResponses = $this->responses()
            ->where('status', 'compliant')
            ->count();

        $totalApplicable = $this->responses()
            ->where('status', '!=', 'not_applicable')
            ->where('status', '!=', 'not_assessed')
            ->count();

        return $totalApplicable > 0
            ? round(($compliantResponses / $totalApplicable) * 100, 2)
            : 0;
    }
}