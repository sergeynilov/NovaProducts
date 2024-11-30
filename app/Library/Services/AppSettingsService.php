<?php

namespace App\Library\Services;

use App\Enums\NovaSettingsParamEnum;
use App\Library\Services\Interfaces\AppSettingsInterface;
use Carbon\Carbon;

/**
 * Class to wrap AppSettings service
 */
readonly class AppSettingsService implements AppSettingsInterface
{
    /**
     * Get setting values by NovaSettingsParamEnum parameter | array of NovaSettingsParamEnum parameters
     *
     * @param NovaSettingsParamEnum|array $param - parameter or array of NovaSettingsParamEnum parameters
     *
     * @return NovaSettingsParamEnum|array|int|Carbon|float|null - depending on passed parameters
     */
    public static function getValue(NovaSettingsParamEnum|array $param): string|array|int|Carbon|float|null {
        if(is_array($param)) {
            $value = nova_get_settings($param->value);
            return $value;
        } else {
            $value = nova_get_settings([$param->value]);
            return $value[$param->value] ?? null;
        }
    }

}
