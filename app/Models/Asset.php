<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'asset_type',
        'owner',
        'department',
        'location',
        'confidentiality_rating',
        'integrity_rating',
        'availability_rating',
        'description',
    ];

    protected $casts = [
        'confidentiality_rating' => 'integer',
        'integrity_rating' => 'integer',
        'availability_rating' => 'integer',
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function risks()
    {
        return $this->hasMany(RiskAssessment::class);
    }

    // Scopes
    public function scopeForTenant($query)
    {
        return $query->where('tenant_id', auth()->user()->current_tenant_id);
    }
}