<?php

namespace App\Nova\Metrics;

use App\Enums\NovaSettingsParamEnum;
use App\Models\Order;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use Laravel\Nova\Nova;
use Outl1ne\NovaSettings\NovaSettings;

class OrdersCompletedByManagerByDays extends Trend
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->countByDays($request, Order::onlyCompleted(), null, 'completed_by_manager_at');
    }


    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            7 => Nova::__('7 Days'),
            10 => Nova::__('10 Days'),
            30 => Nova::__('30 Days'),
            60 => Nova::__('60 Days'),
            90 => Nova::__('90 Days'),
        ];
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|null
     */
    public function cacheFor()
    {
        return now()->addMinutes(NovaSettings::getSetting(NovaSettingsParamEnum::METRIX_CACHING_IN_MINUTES->value));
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'orders-completed-by-manager-by-days';
    }

 /* Title of the card
 *
 * @return string
 */
    public function name(): string
    {
        return 'Number of completed orders by days';
    }

}
