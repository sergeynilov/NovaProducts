<?php

namespace App\Models;

use App\Enums\UserMembershipMarkEnum;
use App\Enums\UserStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\UserStaffScope;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\App;
use Spatie\Permission\Traits\HasRoles;

class StaffUser extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use Notifiable, HasRoles;
    protected $guard_name = 'web';

    protected static function boot()
    {
        parent::boot();
        if(App::isLocal()) {
//            Model::shouldBeStrict();
        }
        static::addGlobalScope(new UserStaffScope);
    }

    protected $fillable = ['name', 'email', 'password', 'status', 'membership_mark' ];

    protected $casts = [ 'created_at' => 'datetime', 'updated_at' => 'datetime', 'status' => UserStatusEnum::class];

    protected $hidden
        = [
            'created_at',
            'updated_at'
        ];


    public function scopeGetById($query, $id): Builder
    {
        return $query->where($this->table . '.id', $id);
    }


//    public function user(): HasOne
//    {
//        return $this->hasOne(User::class);
//    }

    public function scopeGetByUserPermission($query, int|array $permissionId=null): Builder
    {
        if (empty($permissionId)) {
            return $query;
        }

        $query->whereHas('permissions', function ($query) use ($permissionId): void {
            $query->whereIn('permission_id', (array)$permissionId);
        });
        return $query;
    }

}
