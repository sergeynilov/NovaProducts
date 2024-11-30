<?php

namespace App\Nova\Repeater;

use App\Models\Category;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class CategoriesInSubscription extends Repeatable
{
    /**
     * Get the fields displayed by the repeatable.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $categories = Category::get()->pluck('name', 'id');
        return [
            Select::make(__('Category'), 'category_id')
                ->options($categories)
                ->rules('required')
                ->displayUsingLabels()
                ->searchable()->sortable(),
            Boolean::make(__('In subscription'), 'in_subscription')->default(false),
        ];



        /*
        return [
            ID::make(),

            Select::make(__('City'), 'city_id')
                ->options($cities)
                ->rules('required')
                ->displayUsingLabels()
                ->searchable(),

*/
    }
}
