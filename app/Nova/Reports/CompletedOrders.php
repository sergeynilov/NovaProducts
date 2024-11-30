<?php

namespace App\Nova\Reports;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use KirschbaumDevelopment\NovaChartjs\InlinePanel;
use KirschbaumDevelopment\NovaChartjs\NovaChartjs;

class CompletedOrders extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Order>
     */
    public static $model = \App\Models\Order::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
//        \Log::info(varDump(-1, ' -1 indexQuery::'));
        $query->onlyCompleted();

        return parent::indexQuery($request, $query);
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $chartName = 'Completed orders';
        return [
//            ID::make()->sortable(),
//            InlinePanel::make($this, $request, 'Chart Name'),


            NovaChartjs::make('Panel Name', 'novaChartjsMetricValue', function () use ($chartName) {
                return optional($this->novaChartjsMetricValue()->where('chart_name', $chartName)->first())->metric_values ?? [];
            }),

/*            NovaChartjs::make('Panel Name', 'novaChartjsMetricValue',
                function () use ($chartName)) {
                return optional($this->novaChartjsMetricValue()->where('chart_name', $chartName)->first())->metric_values ?? []*/

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
