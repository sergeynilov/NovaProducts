<?php

namespace App\Nova;

use App\Enums\BrandActiveEnum;
use App\Enums\ConfigValueEnum;
use App\Library\Facades\DateConv;
use App\Nova\Actions\Brand\ActivateBrand;
use App\Nova\Actions\Brand\DeactivateBrand;
use App\Nova\Filters\BrandByActive;
use App\Nova\Helpers\BrandHelper;
use Ctessier\NovaAdvancedImageField\AdvancedImage;
use DigitalCreative\ColumnToggler\ColumnTogglerTrait;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Query\Search\SearchableText;

class Brand extends Resource
{
    use ColumnTogglerTrait;
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Brand>
     */
    public static $model = \App\Models\Brand::class;

    /**
     * Get the value that should be displayed as TITLE to represent the resource(in global search).
     *
     * @return string
     */
    public function title()
    {
        return $this->name . '. With status ' . BrandActiveEnum::getLabel($this->active);
    }

    /**
     * Get the value that should be displayed as SUBTITLE to represent the resource(in global search).
     *
     * @return string
     */
    public function subtitle()
    {
        return 'With ' . $this->products_count . ' product(s)';
    }

    public static $globalSearchResults = 10;
    public static $showColumnBorders = false;
    public static $perPageOptions = [10, 25, 100];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
//    public static $search = [
//        'id', 'name','website',
//    ];

    public static function searchableColumns()
    {
        return ['id', new SearchableText('name')/*, new SearchableText('website')*/];
//        TODO
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
        $brandHelper = new BrandHelper();
        $filesystemDisk = ConfigValueEnum::get(ConfigValueEnum::FILESYSTEM_DISK);
        return [
            Heading::make('<p class="text-xl font-bold text-white-50">Brands are used in product editor</p>')->asHtml(),

            ID::make()->sortable(),
            Text::make('Name', 'name')
                ->rules('required', 'max:100')->sortable()
                ->updateRules( 'unique:brands,name,{{resourceId}}')
                ->creationRules( 'unique:brands,name')
                ->textAlign('left')->placeholder('Brand name')
                ->showWhenPeeking(),

            Url::make('Website url', 'website')->rules('required', 'max:255')
                ->textAlign('left')->hideFromIndex()
                ->help('Fill valid url'),

            Boolean::make('Active', 'active')->rules('nullable')->sortable()
                ->showWhenPeeking(),

            AdvancedImage::make(__('Image'), 'image')
                ->disk($filesystemDisk)
                ->path($brandHelper->getUploadDirectory())->prunable()->deletable(true)
                ->resolveUsing(function ($imagePath) use($brandHelper, $filesystemDisk) {
                    if ($imagePath && Storage::disk($filesystemDisk)->exists($imagePath)) {
                        return $imagePath;
                    }
                    return $brandHelper->getDefaultImage();
                })
                ->resize($brandHelper->getImageResizeWidth())->croppable($brandHelper->getImageCroppableRatio())
                ->quality($brandHelper->getImageQuality()),

            Text::make('Products count', function () {
                return $this->resource->products()->count();
            })->textAlign('right')->readonly(),

            Text::make('Created at')->readonly()->resolveUsing(function ($created_at) {
                return DateConv::getFormattedDateTime($created_at);
            })->showOnUpdating()->showOnDetail()->hideWhenCreating(),

            Text::make('Updated at')->readonly()->resolveUsing(function ($updated_at) {
                return DateConv::getFormattedDateTime($updated_at);
            })->showOnUpdating()->hideWhenCreating()->showOnDetail( // in "View" mode "brands/11"
                function (NovaRequest $request, $resource) {
                    return ! empty($updated_at);
                }
            )->showOnUpdating( // in "Edit" mode "brands/11/edit"
                function (NovaRequest $request, $resource) {
                    return ! empty($updated_at);
                }
            )->showOnIndex( // in "List" mode "brands"
                function (NovaRequest $request, $resource) {
                    return ! empty($updated_at);
                }
            ),

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
        return [new BrandByActive];
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
        return [new ActivateBrand, new DeactivateBrand];
    }

    /**
     * Determine if the given resource is authorizable.
     *
     * @return bool
     */
    public static function authorizable()
    {
        return true;
    }
}
