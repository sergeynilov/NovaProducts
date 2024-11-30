<?php

namespace App\Library\Services\User;

use App\Enums\ActionNoteTypeEnum;
use App\Enums\ConfigValueEnum;
use App\Enums\UserStatusEnum;
use App\Library\Facades\LoggedUserFacade;
use App\Models\User;
use App\Models\ActionNote;
use Carbon\Carbon;

class ChangeStatus
{
    public function setNew(int $userId, string $notes): bool
    {
        $user = User::find($userId);
        \Log::info(varDump($userId, ' -1 ? $userId::'));

        if (empty($user) or $user->status === UserStatusEnum::NEW) {
            return false;
        }
        \Log::info(varDump($user->status, ' -12 INSIDE setNew $user->status::'));
        $user->status = UserStatusEnum::NEW;
        $user->updated_at = Carbon::now(ConfigValueEnum::get(ConfigValueEnum::TIMEZONE));
        $user->save();

        ActionNote::create([
            'user_id' => LoggedUserFacade::getLoggedUserId(),
            'model_type' => User::class,
            'model_id' => $user->id,
            'note_type' => ActionNoteTypeEnum::SET_USER_STATUS_NEW,
            'note' => $notes,
        ]);

        return true;
    }

    public function setActive(int $userId, string $notes): bool
    {
        $user = User::find($userId);
        \Log::info(varDump($userId, ' -1 ? $userId::'));

        if (empty($user) or $user->status === UserStatusEnum::ACTIVE) {
            return false;
        }
        \Log::info(varDump($user->status, ' -134 INSIDE setActive $user->status::'));
        $user->status = UserStatusEnum::ACTIVE;
        $user->updated_at = Carbon::now(ConfigValueEnum::get(ConfigValueEnum::TIMEZONE));
        $user->save();

        ActionNote::create([
            'user_id' => LoggedUserFacade::getLoggedUserId(),
            'model_type' => User::class,
            'model_id' => $user->id,
            'note_type' => ActionNoteTypeEnum::SET_USER_STATUS_ACTIVE,
            'note' => $notes,
        ]);

        return true;
    }

    public function setInactive(int $userId, string $notes): bool
    {
        $user = User::find($userId);
        \Log::info(varDump($userId, ' -1 setInactive ? $userId::'));

        if (empty($user) or $user->status === UserStatusEnum::INACTIVE) {
            return false;
        }
        \Log::info(varDump($user->status, ' -12 INSIDE setInactive $user->status::'));
        $user->status = UserStatusEnum::INACTIVE;
        $user->updated_at = Carbon::now(ConfigValueEnum::get(ConfigValueEnum::TIMEZONE));
        $user->save();

        ActionNote::create([
            'user_id' => LoggedUserFacade::getLoggedUserId(),
            'model_type' => User::class,
            'model_id' => $user->id,
            'note_type' => ActionNoteTypeEnum::SET_USER_STATUS_INACTIVE,
            'note' => $notes,
        ]);

        return true;
    }

}
