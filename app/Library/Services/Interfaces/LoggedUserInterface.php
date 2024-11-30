<?php

namespace App\Library\Services\Interfaces;

use App\Enums\IconEnum;
//use App\Enums\LayoutTypeEnum;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Interface for LoggedUser service Class
 */
interface LoggedUserInterface
{
    /**
     * generate and return html of avatar
     *
     * @param string $customClass - class assign to to rsulting image
     *
     * @param bool $showPermissions - if show Permissions of user on  image title
     *
     * @return string - generated html of avatar
     */
    public static function getAvatarHtml(?string $customClass = '', bool $showPermissions = false): string;

    /**
     * Get permissions List with selected items
     *
     * @return bool
     */
    public static function permissionsList(): array;

    /**
     * check if user is logged
     *
     * @return bool
     */
    public static function checkUserLogged(): bool;


    /**
     * get logged user or null if not logged
     *
     * @return Authenticatable | null
     */
    public static function getLoggedUser(): Authenticatable|null;

    /**
     * get logged user id or null if not logged
     *
     * @return int | null
     */
    public static function getLoggedUserId(): int | null;

    /**
     * get notifications of logged user
     *
     * @param bool $onlyUnread - if to show only unread getNotifications
     *
     * @return \Illuminate\Notifications\DatabaseNotificationCollection  - resulting data
     */
    public static function getNotifications(bool $onlyUnread = true): \Illuminate\Notifications\DatabaseNotificationCollection;

}
