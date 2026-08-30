<?php

namespace App\Models;

use Database\Factories\AssessmentResponseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'control_id',
        'status',
        'maturity_level',
        'evidence_notes',
        'evidence_files',
        'gap_description',
        'not_applicable_reason',
        'assigned_to',
        'due_date',
        'assessed_by',
        'assessed_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'evidence_files' => 'array',
        'assessed_at' => 'datetime',
        'maturity_level' => 'integer',
    ];

    protected static function newFactory(): AssessmentResponseFactory
    {
        return AssessmentResponseFactory::new();
    }

    // Relationships
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function control()
    {
        return $this->belongsTo(Control::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assessedBy()
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }

    // Scopes
    public function scopeCompliant($query)
    {
        return $query->where('status', 'compliant');
    }

    public function scopeNonCompliant($query)
    {
        return $query->where('status', 'non_compliant');
    }

    public function scopePartiallyCompliant($query)
    {
        return $query->where('status', 'partially_compliant');
    }

    public function scopeNotAssessed($query)
    {
        return $query->where('status', 'not_assessed');
    }

    // Helper Methods
    public function getStatusColorAttribute(): string
    {
        return [
            'compliant' => 'green',
            'non_compliant' => 'red',
            'partially_compliant' => 'amber',
            'not_applicable' => 'gray',
            'not_assessed' => 'default',
        ][$this->status] ?? 'default';
    }

    public function getMaturityLabelAttribute(): string
    {
        $levels = [
            0 => 'Not Implemented',
            1 => 'Initial/Ad-hoc',
            2 => 'Repeatable but informal',
            3 => 'Defined and documented',
            4 => 'Managed and measured',
            5 => 'Optimized/Continuous improvement',
        ];

        return $this->maturity_level !== null
            ? $levels[$this->maturity_level]
            : 'Not Assessed';
    }
}