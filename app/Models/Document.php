<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id', 'title', 'document_type', 'category', 'current_version',
        'file_path', 'status', 'owner_id', 'approved_by', 'approved_at',
        'review_date', 'description', 'tags',
    ];

    protected $casts = [
        'tags' => 'array',
        'approved_at' => 'datetime',
        'review_date' => 'date',
    ];

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function owner(): BelongsTo { return $this->belongsTo(User::class, 'owner_id'); }
    public function approver(): BelongsTo { return $this->belongsTo(User::class, 'approved_by'); }
    public function versions(): HasMany { return $this->hasMany(DocumentVersion::class); }
    public function reviews(): HasMany { return $this->hasMany(DocumentReview::class); }
    public function controls(): BelongsToMany
    {
        return $this->belongsToMany(Control::class, 'document_control_map');
    }
}