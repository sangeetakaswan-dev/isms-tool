<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentTeam extends Model
{
    use HasFactory;

    protected $table = 'assessment_team';

    protected $fillable = [
        'assessment_id',
        'user_id',
        'role',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Role Constants
    public const ROLE_LEAD_ASSESSOR = 'lead_assessor';
    public const ROLE_ASSESSOR = 'assessor';
    public const ROLE_REVIEWER = 'reviewer';

    public const ROLES = [
        self::ROLE_LEAD_ASSESSOR,
        self::ROLE_ASSESSOR,
        self::ROLE_REVIEWER,
    ];

    // Helpers
    public function isLeadAssessor(): bool
    {
        return $this->role === self::ROLE_LEAD_ASSESSOR;
    }

    public function isAssessor(): bool
    {
        return $this->role === self::ROLE_ASSESSOR;
    }

    public function isReviewer(): bool
    {
        return $this->role === self::ROLE_REVIEWER;
    }
}