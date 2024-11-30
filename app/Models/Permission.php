<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as Model;

class Permission extends Model
{
    //
    protected $table      = 'sppm_permissions';
    protected $primaryKey = 'id';
    public $timestamps = true;

    public function scopeGetUserByName($query, $name= null)
    {
        if (!empty($name)) {
            $query->where($this->table . '.name', $name);
        }
        return $query;
    }

}
