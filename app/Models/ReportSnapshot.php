<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportSnapshot extends Model
{
    protected $fillable = ['tenant_id', 'report_type', 'metrics', 'snapshot_date'];

    protected $casts = [

        'snapshot_date' => 'date',
        'metrics' => 'array',

    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}