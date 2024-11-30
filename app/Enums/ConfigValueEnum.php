<?php

namespace App\Enums;

//use App\Models\Settings;

enum ConfigValueEnum: int
{
    case DATETIME_ASTEXT_FORMAT = 1;
    case DATE_ASTEXT_FORMAT = 2;
    case DATETIME_NUMBERS_FORMAT = 3;
//    case NEWS_REACTIONS_STATISTICS_DAYS = 4;
//    case NEWS_REACTIONS_STATISTICS_LISTING_LIMIT = 5;
//    case PROFILE_LISTING_LIMIT = 6;
    case APP_NAME = 7;
    case TIMEZONE = 8;
//    case COPYRIGHT_TEXT = 9;
//    case SITE_HEADING = 10;
    case DEFAULT_AVATAR = 11;
    case FILESYSTEM_DISK = 12;
    case NOVA_ALLOWED_USERS = 13;
//'nova_allowed_users' => env('NOVA_ALLOWED_USERS', []),
/**/

//    case CAPTCHA_SECRET_KEY = 14;
//    case CAPTCHA_SITE_KEY = 15;
//
//    case HOT_NEWS_LISTING_LIMIT = 16;
//    case NEWS_PER_PAGE = 17;
//    case BACKEND_PER_PAGE = 18;
//    case RSS_IMPORT_TASK_PRIORITY = 19;
//    case RSS_IMPORT_TASK_DEADLINE_AT_HOURS = 20;
//    case CACHED_HOT_NEWS_BLOCK_MINUTES = 21;
//    case CACHED_NEWS_SHOW_DAYS = 22;
//    case AUTO_DELETE_MODELS_AFTER_DAYS = 23;
//    case SUPPORT_SIGNATURE = 24;
//    case NOTIFICATION_TARGET = 25;
//    case USE_DATA_CACHING = 26;
//    case NEWS_RATING_MEMBERSHIP_MARK_CLASSES = 28;

    public static function get(ConfigValueEnum $case, int|string|array|bool $default = ''): int|string|array|bool
    {
        $value = '';
        switch ($case) {
            case self::DATETIME_ASTEXT_FORMAT:
                $value = config('app.datetime_astext_format');
                break;

            case self::DATE_ASTEXT_FORMAT:
                $value = config('app.date_astext_format');
                break;

            case self::DATETIME_NUMBERS_FORMAT:
                $value = config('app.datetime_numbers_format');
                break;


            case self::TIMEZONE:
                $value = config('app.timezone');
                break;

            case self::APP_NAME:
                $value = config('app.name');
                break;


            case self::DEFAULT_AVATAR:
//                $value = $appSettings->getByKey('default_avatar');
//                if (empty($value)) {
//                    $value = $appSettings->setKey('default_avatar', Settings::getValue('default_avatar'));
//                }
//                break;

            case self::FILESYSTEM_DISK:
                $value = config('filesystems.default');
                break;

            case self::NOVA_ALLOWED_USERS:
                $value = config('app.nova_allowed_users');
                break;

//            case self::CAPTCHA_SECRET_KEY:
//                $value = config('app.captcha_secret_key');
//                break;
//            case self::CAPTCHA_SITE_KEY:
//                $value = config('app.captcha_site_key');
//                break;

        }

        return $value ?? $default;
    }

}
