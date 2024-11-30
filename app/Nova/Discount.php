<?php

namespace App\Nova;

use App\Enums\DiscountActiveEnum;
use App\Library\Facades\DateConv;
use App\Nova\Filters\DiscountByActive;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasManyThrough;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Number;
use Eminiarts\Tabs\Traits\HasTabs;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Traits\HasActionsInTabs;
use Laravel\Nova\Query\Search\SearchableText;
use Outl1ne\NovaSortable\Traits\HasSortableRows;


class Discount extends Resource
{
    use HasTabs, HasActionsInTabs, HasSortableRows;

    // Use this Trait
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Discount>
     */
    public static $model = \App\Models\Discount::class;

    /**
     * Get the value that should be displayed as TITLE to represent the resource(in global search).
     *
     * @return string
     */
    public function title()
    {
        $rangeText = '';
        if( !empty($this->active_from) or !empty($this->active_till) ) {
            $rangeText .= '. With active range '.DateConv::getFormattedDate($this->active_from) . '. till ' . DateConv::getFormattedDate($this->active_till);
        }
        return $this->name . $rangeText;
    }

    /**
     * Get the value that should be displayed as SUBTITLE to represent the resource(in global search).
     *
     * @return string
     */
    public function subtitle()
    {
        return "Status: ".DiscountActiveEnum::getLabel($this->active).". Percent: {$this->percent}";
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
/*    public static $search
        = [
            'id', 'name',
        ];*/

    public static function searchableColumns()
    {
        return ['id', new SearchableText('name'), new SearchableText('description')];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->withCount('products');
        return parent::indexQuery($request, $query);
    }


    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        \Log::info(' -1 $this->id::');
        \Log::info(json_encode($this->id));


        return [
            Tabs::make(__('Discount'), [
                Tab::make(__('Details'), [
                    ID::make()->sortable(),
                    Text::make(__('Name'), 'name')
                        ->rules('required', 'max:100')->sortable()
                        ->updateRules('unique:discounts,name,{{resourceId}}')
                        ->creationRules('unique:discounts,name')
                        ->textAlign('left')->placeholder('Discount name')
                        ->showWhenPeeking(),

                    Boolean::make(__('Active'), 'active')->rules('nullable')->sortable()
                        ->showWhenPeeking(),
                    Date::make(__('Active from'), 'active_from')
                        ->displayUsing(fn($value) => $value ? DateConv::getFormattedDate($value) : '')
                        ->sortable(),
                    Date::make(__('Active till'), 'active_till')
                        ->displayUsing(fn($value) => $value ? DateConv::getFormattedDate($value) : '')
                        ->sortable(),

                    Number::make(__('Minimal quality'), 'min_qty',)->min(1)->max(999999)->step(1),
                    Number::make(__('Maximum quality'), 'max_qty')->min(1)->max(999999)->step(1),
                    Number::make(__('Percent'), 'percent')->min(1)->max(100)->step(1)
                        ->showWhenPeeking(),

                    Number::make(__('Order'), 'sort_order',)->min(1)->max(256)->step(1),


                    Text::make(__('Products count'), function () {
                        return $this->resource->products()->count();
                    })->textAlign('right')->readonly(), //->showOnDetail()->showOnUpdating()->showOnPreview(),


                    Trix::make(__('Description'))
                        ->rules('required')
                        ->hideFromIndex()->showOnPreview()->alwaysShow()->withFiles('public')->fullWidth()->stacked(),

                    Text::make('Created at')->readonly()->resolveUsing(function ($created_at) {
                        return DateConv::getFormattedDateTime($created_at);
                    })->showOnUpdating()->showOnDetail()->hideWhenCreating(),

                    Text::make('Updated at')->readonly()->resolveUsing(function ($updated_at) {
                        return DateConv::getFormattedDateTime($updated_at);
                    })->showOnUpdating()->hideWhenCreating()->showOnDetail( // in "View" mode "products/11"
                        function (NovaRequest $request, $resource) {
                            return ! empty($updated_at);
                        }
                    )->showOnUpdating( // in "Edit" mode "products/11/edit"
                        function (NovaRequest $request, $resource) {
                            return ! empty($updated_at);
                        }
                    ),

                ]),

//                Tab::make('Actions', [
//                    $this->actionfield(new \Cog\Laravel\Nova\Ban\Actions\Ban(),), // Add Actions whererver you like.
//                ]),
            ]),
            HasManyThrough::make(name: __('Used by  products'), attribute:  'products', resource: \App\Nova\Product::class),

        ];
    }

    public static function canSort(NovaRequest $request, $resource)
    {
        // Do whatever here, ie:
        // return user()->isAdmin();
        // return $resource->id !== 5;
        return true;
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     *
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
     *
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [new DiscountByActive];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     *
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
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
