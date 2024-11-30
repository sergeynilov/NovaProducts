<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class ModelHasPermission extends Model
{
    protected $table      = 'sppm_model_has_permissions';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    public function scopeGetByPermissionId($query, $permission_id)
    {
        return $query->where($this->table . '.permission_id', $permission_id);
    }


    public function scopeGetByModelId($query, $model_id)
    {
        return $query->where($this->table . '.model_id', $model_id);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'model_id');
    }
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }


}
