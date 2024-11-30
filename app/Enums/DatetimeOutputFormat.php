<?php

namespace App\Enums;

enum DatetimeOutputFormat: string
{
    case AGO_FORMAT = 'ago_format';
    case AS_TEXT = 'astext';
    case AS_NUMBERS = 'numbers';
}

/*use BenSampo\Enum\Enum;

final class DatetimeOutputFormat extends Enum
{
    public const AGO_FORMAT = 'ago_format';
    public const AS_TEXT = 'astext';
    public const AS_NUMBERS = 'numbers';
}*/
