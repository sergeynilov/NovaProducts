<?php

namespace App\Library\Services\Interfaces;

use App\Enums\NovaSettingsParamEnum;
use Carbon\Carbon;

/**
 * Interface for AppSettings service Class
 */
interface AppSettingsInterface
{
    /**
     * Get setting values by NovaSettingsParamEnum parameter | array of NovaSettingsParamEnum parameters
     *
     * @param NovaSettingsParamEnum|array $param - parameter or array of NovaSettingsParamEnum parameters
     *
     * @return string|array|int|Carbon|float|null - depending on passed parameters
     */
    public static function getValue(NovaSettingsParamEnum|array $param): string|array|int|Carbon|float|null;

}
