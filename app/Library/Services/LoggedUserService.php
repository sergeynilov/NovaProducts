<?php

namespace App\Library\Services;

use App\Enums\ConfigValueEnum;
use App\Library\Services\Interfaces\LoggedUserInterface;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

/**
 * Class to wrap LoggedUser service
 */
readonly class LoggedUserService implements LoggedUserInterface
{
    /**
     * generate and return html of avatar
     *
     * @param string $customClass - class assign to to rrsulting image
     *
     * @param bool $showPermissions - if show Permissions of user on  image title
     *
     * @return string - generated html of avatar
     */
    public static function getAvatarHtml(?string $customClass = '', bool $showPermissions = false): string
    {
        if (empty(auth()->user())) {
            return '';
        }

        $loggedUser = \Auth::user();
//        if (!blank($imagePath) and Storage::disk(ConfigValueEnum::get(ConfigValueEnum::FILESYSTEM_DISK))->exists( $imagePath) ) {
        /*  */

        $avatarUrl = ( ! blank($loggedUser->avatar) and Storage::disk(ConfigValueEnum::get(ConfigValueEnum::FILESYSTEM_DISK))->exists($loggedUser->avatar)) ? User::getImageStorageUrl($loggedUser->avatar) : ConfigValueEnum::get(ConfigValueEnum::DEFAULT_AVATAR);
//        $avatarUrl = ! empty($loggedUser->avatar) ? User::getImageStorageUrl($loggedUser->avatar) : ConfigValueEnum::get(ConfigValueEnum::DEFAULT_AVATAR);

        $permissionText = $loggedUser->permissions
            ->map(fn($permission) => \Str::title(\Str::replace('_', ' ', $permission->name)))
            ->implode(',');
        if ( ! empty($permissionText)) {
            $permissionText = ': ' . $permissionText;
        }

        $permissionText = trimRightSubString($permissionText, ', ');
        $retHtml = '<img src="' . $avatarUrl . '" class=" max-w-full max-h-full inline ' . $customClass . ' " alt="' . $loggedUser->name . '" title="' . '      ' . $loggedUser->id . '=>' . $loggedUser->name . $permissionText . '" loading="lazy"/>';

        return $retHtml;
    }

    /**
     * Get permissions List with selected items
     *
     * @return bool
     */
    public static function permissionsList(): array
    {

        $loggedUser = \Auth::user();
        if(empty($loggedUser)) return [];
        $permissions = $loggedUser->permissions->pluck('id')->toArray();

        $userPermissionsSelectionItems = Permission::getSelectionItems($permissions);
        foreach ($userPermissionsSelectionItems as $key => $userPermissionsSelectionItem) {
            if(in_array($userPermissionsSelectionItem['id'], $permissions)) {
                $userPermissionsSelectionItems[$key]['selected'] = true;
            }
        }
        return $userPermissionsSelectionItems;
    }

    /**
     * check if user is logged
     *
     * @return bool
     */
    public static function checkUserLogged(): bool
    {
        return ! empty(auth()->user());
    }

    /**
     * get logged user or null if not logged
     *
     * @return Authenticatable | null
     */
    public static function getLoggedUser(): Authenticatable|null
    {
        return ! empty(auth()->user()) ? auth()->user() : null;
    }

    /**
     * get logged user id or null if not logged
     *
     * @return int | null
     */
    public static function getLoggedUserId(): int|null
    {
        return ! empty(auth()->user()) ? auth()->user()->id : null;
    }

    /**
     * get notifications of logged user
     *
     * @param bool $onlyUnread - if to show only unread getNotifications
     *
     * @return LengthAwarePaginator - resulting data
     */
    public static function getNotifications(bool $onlyUnread = true
    ): \Illuminate\Notifications\DatabaseNotificationCollection {

        /*$collection = collect(['taylor', 'abigail', null])->map(function (?string $name) {
    return strtoupper($name);
})->reject(function (string $name) {
    return empty($name);
});*/
        return auth()->user()->{$onlyUnread ? 'unreadNotifications' : 'notifications'}()->latest()->get()
            ->map(function ($notification) {
//                \Log::info(varDump($notification->data, ' -1 $notification->data::'));
                $dataArray = $notification->data['data'] ?? [];
                $dataText = '';
                if ( ! empty($dataArray[0]) and is_array($dataArray) and count($dataArray) === 1) {
                    foreach ($dataArray[0] as $key => $value) {
                        $dataText .= $key . '=' . $value . '<br>';
                    }
                } else {
                    if ( ! empty($dataArray) and count($dataArray) > 1) {
                        foreach ($dataArray as $key => $value) {
                            try {
                                if (is_array($value)) {
                                    foreach ($value as $key2 => $value2) {
                                        $dataText .= $key2 . '=' . $value2 . '<br>';
                                    }
                                } else {
                                    $dataText .= $key . '=' . $value . '<br>';
                                }
                            } catch (\Exception $e) {
                                \Log::info($e->getMessage());
                                \Log::info(varDump($key, ' -1 ERROR $key::'));
                                \Log::info(varDump($value, ' -1 $value::'));
                            }
                        }
                    }
                }
                $collection = \Str::of($notification->type)->explode('\\');
                $notification->type = \Str::title(\Str::replace('_', ' ',
                    \Str::snake(! empty($collection[count($collection) - 1]) ? $collection[count($collection) - 1] : $notification->type)),
                    ' ');
                $notification->dataText = $dataText;

                return $notification;
            });
    }

}
