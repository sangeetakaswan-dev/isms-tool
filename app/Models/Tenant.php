<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'logo_path',
        'industry',
        'size',
        'contact_email',
        'contact_phone',
        'address',
        'city',
        'state',
        'country',
        'pincode',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tenant) {
            if (!$tenant->slug) {
                $tenant->slug = Str::slug($tenant->name) . '-' . Str::random(6);
            }
        });
    }

    // Relationships
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role', 'permissions', 'is_default')
            ->withTimestamps();
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    // Scopes
    public function scopeByIndustry($query, $industry)
    {
        return $query->where('industry', $industry);
    }

    // Helper Methods
    public function getLogoUrlAttribute()
    {
        return $this->logo_path
            ? asset('storage/' . $this->logo_path)
            : null;
    }

    public function getUserRole(User $user)
    {
        $membership = $this->users()
            ->where('user_id', $user->id)
            ->first();

        return $membership ? $membership->pivot->role : null;
    }

    public function isOwner(User $user)
    {
        return $this->getUserRole($user) === 'owner';
    }
}