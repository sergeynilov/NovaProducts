<?php

namespace App\Nova\Repeater;

use App\Models\City;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Textarea;
class ProductCities extends Repeatable
{
    /**
     * Get the fields displayed by the repeatable.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $cities = City::get()->pluck('name', 'id');
        return [
            ID::make(),

            Select::make(__('City'), 'city_id')
                ->options($cities)
                ->rules('required')
                ->displayUsingLabels()
                ->searchable()->sortable()->sortable(),

//            Number::make('Quantity')->rules('required', 'numeric'),
//            Textarea::make('Description')->rules('required', 'max:255'),

//            Currency::make('Price')->rules('required', 'numeric'),
            /*
CREATE TABLE `products_cities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `city_id` smallint unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,*/
        ];
    }
}
