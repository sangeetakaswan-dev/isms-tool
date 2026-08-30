<?php

namespace App\Models;

use Database\Factories\ControlFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Control extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain_id',
        'control_id',
        'title',
        'description',
        'implementation_guidance',
        'category',
        'control_type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ]; 

    protected static function newFactory(): ControlFactory
    {
        return ControlFactory::new();
    }

     public function responses()
    {
        return $this->hasMany(AssessmentResponse::class);
    }

    // Relationships
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function assessmentResponses()
    {
        return $this->hasMany(AssessmentResponse::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeAnnexA($query)
    {
        return $query->whereHas('domain', function ($q) {
            $q->where('clause_type', 'annex_a');
        });
    }

    public function scopeMainClause($query)
    {
        return $query->whereHas('domain', function ($q) {
            $q->where('clause_type', 'main_clause');
        });
    }

    // Helper Methods
    public function getFullDescriptionAttribute()
    {
        return $this->implementation_guidance
            ? $this->description . "\n\nImplementation Guidance:\n" . $this->implementation_guidance
            : $this->description;
    }
}