<?php

namespace App\Models\Scopes;

use App\Models\ModelHasPermission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class UserStaffScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $staffUsersIds = ModelHasPermission::get()->pluck('model_id')->unique();
        $builder->whereIn('id', $staffUsersIds);
    }
}
