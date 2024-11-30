<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\MetricTableRow;
use Laravel\Nova\Metrics\Table;
use Imumz\NovaAccordionField\NovaAccordionField;

class NewReleases extends Table
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {

        $array = [
            0 => [
                'name' => 'John Doe',
                'text' => 'john@example.com'
            ],
            1 => [
                'name' => 'Jane Doe',
                'text' => 'jane@example.com'
            ],
            2 => [
                'name' => 'Tom Doe',
                'text' => 'tom@example.com'
            ],
        ];

        return [
            MetricTableRow::make()
                ->icon('check-circle')
                ->iconClass('text-green-500')
                ->title('Silver Surfer')
                ->subtitle('In every part of the globe it is the same!'),

            NovaAccordionField::make('')
                ->data($array)
                ->title('name')
                ->description('text')
        ];
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|null
     */
    public function cacheFor()
    {
        // return now()->addMinutes(5);
    }
}
