<?php

namespace App\Models;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Risk extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 'asset_id', 'title', 'description', 'category',
        'likelihood', 'impact', 'risk_score', 'risk_level', 'status',
        'owner_id', 'identified_at', 'reviewed_at',
    ];

    protected $casts = [
        'likelihood'    => 'integer',
        'impact'        => 'integer',
        'risk_score'    => 'integer',
        'identified_at' => 'date',
        'reviewed_at'   => 'datetime',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope());
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function asset(): BelongsTo { return $this->belongsTo(Asset::class); }
    public function owner(): BelongsTo { return $this->belongsTo(User::class, 'owner_id'); }
    public function treatmentPlans(): HasMany { return $this->hasMany(RiskTreatmentPlan::class, 'risk_id'); }

    public function scopeByLevel($q, string $l) { return $q->where('risk_level', $l); }
    public function scopeByStatus($q, string $s) { return $q->where('status', $s); }
    public function scopeOpen($q) { return $q->whereNotIn('status', ['closed']); }
}