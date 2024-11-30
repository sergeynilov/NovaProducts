<?php

namespace App\Models;

use App\Enums\UserMembershipMarkEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserProfile extends Model
{
    protected $table = 'user_profile';
    public $timestamps = true;

    protected $fillable = ['membership_mark', 'phone', 'website', 'notes', 'user_id'];

    protected $casts = [ 'created_at' => 'datetime', 'updated_at' => 'datetime', 'membership_mark' => UserMembershipMarkEnum::class];

    public function scopeGetByUserId($query, int $userId = null): Builder
    {
        if ( ! empty($userId)) {
            $query->where($this->table . '.user_id', $userId);
        }

        return $query;
    }
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

}

