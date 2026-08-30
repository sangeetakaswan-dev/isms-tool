<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_path',
        'phone',
        'job_title',
        'department',
        'is_active',
        'current_tenant_id',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    // Relationships
    public function tenants()
    {
        return $this->belongsToMany(Tenant::class)
            ->withPivot('role', 'permissions', 'is_default')
            ->withTimestamps();
    }

    public function assessmentTeam()
    {
        return $this->belongsToMany(Assessment::class, 'assessment_team')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function belongsToTenant(int $tenantId): bool
    {
        return $this->tenants()->where('tenants.id', $tenantId)->exists();
    }
    public function currentTenant()
    {
        return $this->belongsTo(Tenant::class, 'current_tenant_id');
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class, 'email', 'email');
    }

    public function assignedResponses()
    {
        return $this->hasMany(AssessmentResponse::class, 'assigned_to');
    }

    public function assessedResponses()
    {
        return $this->hasMany(AssessmentResponse::class, 'assessed_by');
    }

    public function leadAssessments()
    {
        return $this->hasMany(Assessment::class, 'lead_assessor_id');
    }

    // Helper Methods
    public function getAvatarUrlAttribute()
    {
        return $this->avatar_path
            ? asset('storage/' . $this->avatar_path)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function switchTenant(Tenant $tenant)
    {
        $this->current_tenant_id = $tenant->id;
        $this->save();

        session(['current_tenant_id' => $tenant->id]);
    }

    public function hasTenantRole(Tenant $tenant, string $role)
    {
        return $this->tenants()
            ->where('tenant_id', $tenant->id)
            ->where('role', $role)
            ->exists();
    }
}