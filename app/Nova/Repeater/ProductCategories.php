<?php

namespace App\Nova\Repeater;

use App\Models\Category;
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\ID;

class ProductCategories extends Repeatable
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
            ID::make(),

            Select::make(__('Category'), 'category_id')
                ->options($categories)
                ->rules('required')
                ->displayUsingLabels()
                ->searchable()->sortable(),

        ];


//        return [
//        ];
    }
}
