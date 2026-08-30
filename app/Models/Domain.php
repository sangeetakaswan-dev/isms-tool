<?php

namespace App\Models;

use Database\Factories\DomainFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'clause_type',
        'sort_order',
    ];
    
protected static function newFactory(): DomainFactory
    {
        return DomainFactory::new();
    }
    // Relationships
    public function controls()
    {
        return $this->hasMany(Control::class);
    }

    // Scopes
    public function scopeMainClauses($query)
    {
        return $query->where('clause_type', 'main_clause');
    }

    public function scopeAnnexA($query)
    {
        return $query->where('clause_type', 'annex_a');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Helper Methods
    public function getControlCountAttribute()
    {
        return $this->controls()->count();
    }
}