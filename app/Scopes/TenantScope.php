<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $user = Auth::user();
        
        if ($user && $user->current_tenant_id) {
            $table = $model->getTable();
            
            // Only apply if table has tenant_id column
            if (\Schema::hasColumn($table, 'tenant_id')) {
                $builder->where($table . '.tenant_id', $user->current_tenant_id);
            }
        }
    }
}