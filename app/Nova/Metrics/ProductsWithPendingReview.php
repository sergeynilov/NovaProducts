<?php

namespace App\Nova\Metrics;

use App\Enums\NovaSettingsParamEnum;
use App\Enums\ProductStatusEnum;
use App\Models\Product as ProductModel;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Nova;
use Outl1ne\NovaSettings\NovaSettings;

class ProductsWithPendingReview extends Value
{
    /* Title of the card
    *
    * @return string
    */
    public function name(): string
    {
        return 'Number of products with pending review';
    }


    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, ProductModel::getByStatus(ProductStatusEnum::PENDING_REVIEW));
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            'ALL' => Nova::__('All time'),
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
}
