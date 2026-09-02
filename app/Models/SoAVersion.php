<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoAVersion extends Model
{
    protected $table = 'soa_versions';  // ✅ Add this line

    protected $fillable = [
        'assessment_id',
        'version',
        'data_snapshot',
        'created_by',
    ];

    protected $casts = [
        'data_snapshot' => 'array',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}