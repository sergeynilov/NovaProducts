<?php

namespace App\Nova\Metrics;

use App\Enums\ConfigValueEnum;
use App\Enums\NovaSettingsParamEnum;
use App\Enums\OrderStatusEnum;
use App\Models\Order;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;
use Outl1ne\NovaSettings\NovaSettings;

class OrdersByStatusPieChart extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
//        return $this->count($request, Order::class, 'status');
        return $this->count($request, Order::class, 'status')
            ->label(fn ($value) => match ($value) {
                null => 'None',
                default => OrderStatusEnum::getLabel(OrderStatusEnum::tryFrom($value))
            });
    }

    /* Title of the card
     *
     * @return string
     */
    public function name(): string
    {
        return 'Number of orders by status';
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
        return 'orders-by-status-pie-chart';
    }
}
