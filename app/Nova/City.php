<?php

namespace App\Nova;

use App\Nova\Filters\CityByCountry;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
//use AntonioSiles\Nova4CardMap\Nova4CardMap;
use Imumz\Nova4FieldMap\Nova4FieldMap;
use Laravel\Nova\Query\Search\SearchableText;

class City extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\City>
     */
    public static $model = \App\Models\City::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
//    public static $title = 'address';


    /**
     * Get the value that should be displayed as TITLE to represent the resource(in global search).
     *
     * @return string
     */
    public function title()
    {
        return $this->address;
    }

    /**
     * Get the value that should be displayed as SUBTITLE to represent the resource(in global search).
     *
     * @return string
     */
    public function subtitle()
    {
        return "Postal code: " . $this->postal_code . ', Region: ' . $this->region;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
//    public static $search = [
//        'address', 'postal_code', 'region',
//    ];

    public static function searchableColumns()
    {
        return ['id', new SearchableText('address'), new SearchableText('postal_code'), new SearchableText('region')];
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
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Address'), 'address')
                ->rules('required', 'max:100')->sortable()
                ->textAlign('left')->placeholder('In format City, Country'),

            Text::make(__('Postal code'), 'postal_code')
                ->rules('nullable', 'max:10')->sortable()
                ->textAlign('right')->placeholder('In different countries Postal code has different format'),

            Text::make(__('Country'), 'country')
                ->rules('required', 'max:50')->sortable()
                ->textAlign('left')->placeholder('Code of country'),

            Text::make(__('Region'), 'region')
                ->rules('required', 'max:50')->sortable()
                ->textAlign('left')->placeholder('In format City, Country'),

            Number::make(__('Latitude'), 'geo_lat')->min(-90)->max(90)->required()->step('any'),
            Number::make(__('Longitude'), 'geo_lon')->min(-180)->max(180)->required()->step('any'),

            Text::make('Products count', function () {
                return $this->resource->products()->count();
            })->textAlign('right')->readonly(),

            Nova4FieldMap::make('Map View')
                ->type('LatLon')
                ->point($this->geo_lat,$this->geo_lon)
                ->popup('popup')
                ->height('400px'),

//LeafletMap::make('Map View'),
/*            (new Nova4CardMap())
//                ->type('LatLon')
//                ->point('-6.081689', '145.391881')
//                ->onlyOnDetail()
                ->width("1/2"),//->height('600px'), //,*/

            HasMany::make(name: __('Has products'), attribute:  'products', resource: \App\Nova\Product::class),
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
        return [new CityByCountry];
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
