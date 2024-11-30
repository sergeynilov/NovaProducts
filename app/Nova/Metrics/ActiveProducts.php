<?php

namespace App\Nova\Metrics;

use App\Enums\ProductStatusEnum;
use App\Models\Product;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Progress;

class ActiveProducts extends Progress
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $productsCount = Product::count();
        // 100/ 10 *8
        return $this->count($request, Product::class, function ($query) {
            return $query->where('status', ProductStatusEnum::ACTIVE);
        }, target: 100 / $productsCount);
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'active-products';
    }
}
