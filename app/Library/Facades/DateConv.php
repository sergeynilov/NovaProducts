<?php

namespace App\Library\Facades;

use App\Enums\ConfigValueEnum;
use Carbon\Carbon;

class DateConv
{
//    protected static $time_format = 'H:i';
//    protected static $date_numbers_format = 'Y-m-d';
////    protected static $datetime_numbers_format = 'Y-m-d H:i';
//    protected static $date_astext_format = 'j F, Y';
//    protected static $datetime_astext_format = 'j F, Y g:i:s A';

    public static function getFormattedTime($time = ''): string
    {
        if (empty($time)) {
            return '';
        }
        $value = Carbon::parse($time);

        return $value->format(self::$time_format);
    }

    public static function getFormattedDateTime($datetime): string
    {
//        \Log::info(varDump($datetime, ' -10 getFormattedDateTime$datetime::'));
//        \Log::info(varDump(ConfigValueEnum::get(ConfigValueEnum::DATETIME_ASTEXT_FORMAT), ' -1 ConfigValueEnum::get(ConfigValueEnum::DATETIME_ASTEXT_FORMAT)::'));
        $formattedValue = Carbon::createFromTimestamp(strtotime($datetime), ConfigValueEnum::get(ConfigValueEnum::TIMEZONE))->format(ConfigValueEnum::get(ConfigValueEnum::DATETIME_ASTEXT_FORMAT));
        return $formattedValue;

    }
    public static function isValidTimeStamp($timestamp)
    {
        if (empty($timestamp)) {
            return false;
        }
        //        \Log::info( '-1 isValidTimeStamp gettype($timestamp)::' . print_r( gettype($timestamp), true  ) );
        //        \Log::info( '-1 isValidTimeStamp $timestamp::' . print_r( $timestamp, true  ) );

        if (gettype($timestamp) == 'object') {
            $timestamp = $timestamp->toDateTimeString();
        }

        return ((string)(int)$timestamp === (string)$timestamp)
               && ($timestamp <= PHP_INT_MAX)
               && ($timestamp >= ~PHP_INT_MAX);
    }

    /* 128

If you read the Carbon docs to get what you want you call the diffForHumans() method.

<?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($comment->created_at))->diffForHumans() */
    public static function getFormattedDate($date): string
    {
        if (empty($date)) {
            return '';
        }

        if (! self::isValidTimeStamp($date)) {
            return Carbon::createFromTimestamp(strtotime($date), ConfigValueEnum::get(ConfigValueEnum::TIMEZONE))->format(ConfigValueEnum::get(ConfigValueEnum::DATE_ASTEXT_FORMAT));
        }

        return Carbon::createFromTimestamp($date, ConfigValueEnum::get(ConfigValueEnum::TIMEZONE))->format(ConfigValueEnum::get(ConfigValueEnum::DATE_ASTEXT_FORMAT));
    }

/*    public static function getDateFormat(\App\Enums\DatetimeOutputFormat $format): string
    {
        if ($format == \App\Enums\DatetimeOutputFormat::AS_NUMBERS->value) {
            return self::$date_numbers_format;
        }
        if ($format == \App\Enums\DatetimeOutputFormat::AS_TEXT->value) {
            return self::$date_astext_format;
        }

        return self::$date_numbers_format;
    }
*/
    public static function getDateTimeFormat(\App\Enums\DatetimeOutputFormat $format): string
    {
        if ($format === \App\Enums\DatetimeOutputFormat::AS_NUMBERS->value) {
            return ConfigValueEnum::get(ConfigValueEnum::DATETIME_NUMBERS_FORMAT);
        }
        if ($format == \App\Enums\DatetimeOutputFormat::AS_TEXT->value) {
            return ConfigValueEnum::get(ConfigValueEnum::DATETIME_ASTEXT_FORMAT);
        }

        return ConfigValueEnum::get(ConfigValueEnum::DATETIME_NUMBERS_FORMAT);
    }
}
