<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\ActionNoteTypeEnum;
use App\Enums\UserStatusEnum;
use App\Library\Facades\LoggedUserFacade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Cog\Contracts\Ban\Bannable as BannableContract;
use Cog\Laravel\Ban\Traits\Bannable;

class User extends Authenticatable  implements BannableContract
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasPermissions;
    use Bannable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'password', 'status', 'avatar', 'banned_at' ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden
        = [
            'password',
            'remember_token',
        ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts
        = [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatusEnum::class,
        ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::updating(function ($user) {
            if ($user->isDirty('banned_at')) {


                if(!empty($user->banned_at)) { // USER IS BANNED
                    $user->status = UserStatusEnum::BANNED;

                    ActionNote::create([
                        'user_id' => LoggedUserFacade::getLoggedUserId(),
                        'model_type' => User::class,
                        'model_id' => $user->id,
                        'note_type' => ActionNoteTypeEnum::SET_USER_IS_BANNED,
                        'note' => (!empty($request->comment)) ? $request->comment : 'User is banned',
                    ]);
                }
                if(empty($user->banned_at) and $user->getOriginal('status') === UserStatusEnum::BANNED) { // USER IS UNBANNED
                    $user->status = UserStatusEnum::ACTIVE;
                    ActionNote::create([
                        'user_id' => LoggedUserFacade::getLoggedUserId(),
                        'model_type' => User::class,
                        'model_id' => $user->id,
                        'note_type' => ActionNoteTypeEnum::SET_USER_IS_UNBANNED,
                        'note' => 'User is unbanned',
                    ]);
                }
            }
        });
    }

    public function products(): HasMany
    {
        return $this->hasmany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasmany(Order::class, 'creator_id', 'id');
    }

    public function userProfile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }


    public function scopeGetByStatus($query, UserStatusEnum $status = null): Builder
    {
//        \Log::info(varDump($status, ' -1 scopeGetByStatus $status::'));
        if ( ! empty($status)) {
            $query->where($this->table.'.status', $status);
        }

        return $query;
    }

    public function scopeGetOnlyBanned($query)
    {
        return $query->where($this->table . '.status', UserStatusEnum::BANNED);
    }



}
