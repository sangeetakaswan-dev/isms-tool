<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoAEntry extends Model
{
    use HasFactory;

    protected $table = 'soa_entries';  // ✅ Add this line

    protected $fillable = [
        'assessment_id',
        'control_id',
        'applicable',
        'justification',
        'implementation_status',
        'implementation_description',
        'exclusion_reason',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'applicable' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function control(): BelongsTo
    {
        return $this->belongsTo(Control::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}