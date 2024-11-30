<?php

namespace App\Nova;

use App\Enums\DatetimeOutputFormat;
use App\Enums\ProductStatusEnum;
use App\Library\Facades\DateConv;
use App\Library\Facades\LoggedUserFacade;
use App\Nova\Actions\Product\SetStatusActive;
use App\Nova\Actions\Product\SetStatusDraft;
use App\Nova\Actions\Product\SetStatusInactive;
use App\Nova\Actions\Product\SetStatusPendingReview;
use App\Nova\Filters\ProductByBrand;
use App\Nova\Filters\ProductByStatus;
use App\Nova\Filters\ProductsWithoutStockQty;
use App\Nova\Helpers\ProductHelper;
use App\Nova\Metrics\ActiveProducts;
use App\Nova\Metrics\ProductsWithPendingReview;
use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use DigitalCreative\ColumnToggler\ColumnTogglerTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\Audio;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use ShuvroRoy\NovaTabs\Tab;
use ShuvroRoy\NovaTabs\Tabs;
use ShuvroRoy\NovaTabs\Traits\HasActionsInTabs;
use ShuvroRoy\NovaTabs\Traits\HasTabs;
use Timothyasp\Badge\Badge;
use MateuszPeczkowski\NovaHeartbeatResourceField\NovaHeartbeatResourceField;
use MateuszPeczkowski\NovaHeartbeatResourceField\Traits\HasNovaHeartbeats;
use Titasgailius\SearchRelations\SearchesRelations;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;
use Laravel\Nova\Query\Search\SearchableText;

class Product extends Resource
{
    use HasTabs, HasActionsInTabs;
    use ColumnTogglerTrait;
    use HasNovaHeartbeats;
    use SearchesRelations;


    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Product>
     */
    public static $model = \App\Models\Product::class;

    /**
     * Get the value that should be displayed as TITLE to represent the resource(in global search).
     *
     * @return string
     */
    public function title()
    {
        return $this->title . '. With price ' . $this->regular_price .'. By ' . $this->user->name;
    }


    /**
     * Get the value that should be displayed as SUBTITLE to represent the resource(in global search).
     *
     * @return string
     */
    public function subtitle()
    {
        return "Status: ".ProductStatusEnum::getLabel($this->status).". Brand: {$this->brand->name}";
    }

    public static $globalSearchResults = 10;

//    public static $tableStyle = 'tight';
    public static $showColumnBorders = false;
    public static $perPageOptions = [10, 25, 100];

    /**
     * The action used for the click on the table row. Available options are 'view', 'select' and 'update'.
     *
     * @var string
     */
    /**
     * The columns that should be searched.
     *
     * @var array
     */
/*    public static $search
        = [
            'title', 'short_description', 'description'
        ];*/

    public static function searchableColumns()
    {
        return ['id', new SearchableText('title'), new SearchableText('short_description'), new SearchableText('description')];
    }

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'brand' => ['name'],  // NovaProducts.brands
        'user' => ['name', 'email'],
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
//        \Log::info(varDump(-1, ' -1 indexQuery::'));
        $query->with('user')->withCount('orderItems')->withCount('discountProducts')
            ->withCount('cities');//->withCount('categories');

        return parent::indexQuery($request, $query);
    }

    public static function relatableQuery(NovaRequest $request, $query)
    {
        \Log::info(varDump(-10, ' -10 relatableQuery::'));

        return $query;
    }

    public static function relatableTags(NovaRequest $request, $query)
    {
        \Log::info(varDump(-10, ' -10 relatableTags::'));

        return $query;
    }

    public static function relatableTeams(NovaRequest $request, $query, Field $field)
    {
        \Log::info(varDump(-10, ' -10 relatableTeams::'));

        return $query;
    }

    public static function scoutQuery(NovaRequest $request, $query)
    {
        \Log::info(varDump(-10, ' -10 scoutQuery::'));

        return $query;
    }

    /**
     * Get the fields displayed by listing ofthe resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     *
     * @return array
     */
    public function fieldsForIndex(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            NovaHeartbeatResourceField::make('Heartbeat')
                ->resourceId($this->id)->allowRetake(),
            Badge::make(__('Status'), 'status')->required()
                ->options(ProductStatusEnum::getStatusSelectionItems())
                ->hideFromDetail()->hideWhenUpdating()
                ->colors(ProductStatusEnum::getStatusColors(hexValue: true))
                ->displayUsingLabels(),
            DateTime::make(__('Published at'), 'published_at')
                ->displayUsing(fn($value) => $value ? DateConv::getFormattedDateTime($value) : '-'),
            Text::make(__('Title'), 'title')->sortable()
                ->textAlign('left'),
            BelongsTo::make('Brand')->required()->sortable()
                ->textAlign('left'),
            Boolean::make(__('In stock'), 'in_stock')->sortable(),
        ];
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
        $productHelper = new ProductHelper();
        [$isEditor, $isCreate, $headingBgColor, $editorTitle, $status] = $productHelper->getEditorProps($request);
        return [
            Heading::make('<p class="text-xl font-bold '.$headingBgColor.'">'.$editorTitle.'</p>')->asHtml()->fullWidth(),

            Tabs::make(__('Product'), [
                Tab::make(__('Product details'), [
                    ID::make()->readonly(),
                    Hidden::make('user_id')->default(LoggedUserFacade::getLoggedUserId()),
                    NovaHeartbeatResourceField::make('Heartbeat')
                        ->resourceId($this->id)->allowRetake(),

                    Hidden::make('sku')->default(Str::orderedUuid()),

//                    Badge::make(__('Status'), 'status')->required()
//                        ->options(ProductStatusEnum::getStatusSelectionItems())
//                        ->hideFromDetail()->hideWhenUpdating()
//                        ->colors(ProductStatusEnum::getStatusColors(hexValue: true))
//                        ->displayUsingLabels()->fullWidth(),

                    DateTime::make(__('Published at'), 'published_at')
                        ->displayUsing(fn($value) => $value ? DateConv::getFormattedDateTime($value) : '--')
                        ->dependsOn(['status'], function (DateTime $field, NovaRequest $request, FormData $formData) use ($isCreate) {
                            if($isCreate) { // status manually changed only in create mode
                                $field->hide();
/*                                \Log::info(' -0 $field::');
                                \Log::info(json_encode($field));

                                \Log::info(' -1 $formData::');
                                \Log::info(json_encode($formData));

                                \Log::info(' -198 $formData->status::');
                                \Log::info(json_encode($formData->status));*/

                                if ($formData->status === ProductStatusEnum::ACTIVE->value) {
                                    $field->show();
                                }
                            }
                        })
                        ->showOnDetail(function () use ($status) { // in view mode "/nova/resources/products/17"
                            return $status === ProductStatusEnum::ACTIVE;
                        })
                        ->showOnUpdating(function () use ($status) { // in edit mode "/nova/resources/products/17/edit"
                            return $status === ProductStatusEnum::ACTIVE;
                        })
                        ->fullWidth(),

                    BelongsTo::make(__('Parent Category'), attribute: 'category', resource: \App\Nova\Category::class)
                        ->rules('required', Rule::exists('categories', 'id')->whereNull('parent_id'))
                        ->relatableQueryUsing(function (NovaRequest $request, Builder $query) {
                            $query->whereNull('parent_id');
                        })
                        ->fullWidth(),

                    BelongsTo::make(__('Category'), /*'category',*/ resource: \App\Nova\Category::class)
                        ->dependsOn(['category'], function (BelongsTo $field, NovaRequest $request, FormData $formData) {
                            if ($formData->category === null) {
                                $field->hide();
                            } //else {
                                $field->relatableQueryUsing(function (NovaRequest $request, Builder $query) use($formData) {
                                    $query->where('parent_id', $formData->category);
                                });

//                            }
                        })
                        ->relatableQueryUsing(function (NovaRequest $request, Builder $query) {
                            $query->whereNotNull('parent_id');
                        })
                        ->rules('required', Rule::exists('categories', 'id')->whereNotNull('parent_id'))

                        ->/*hideWhenCreating()->*/fullWidth(),

                    Text::make(__('Title'), 'title')
                        ->textAlign('left')->placeholder('Product Title')
                        ->rules('required', 'max:255')
                        ->updateRules('unique:products,title,{{resourceId}}')
                        ->creationRules('unique:products,title')->fullWidth(),

                    BelongsTo::make('Brand')->required()
                        ->textAlign('left')->placeholder('Select brand')->fullWidth(),

                    Text::make(__('Order items count'), function () {
                        return $this->resource->orderItems()->count();
                    })->textAlign('right')->readonly()->showOnDetail()->showOnUpdating()->showOnPreview()->fullWidth(),

                    Text::make(__('Has discounts count'), function () {
                        return $this->resource->discountProducts()->count();
                    })->textAlign('right')->readonly()->showOnDetail()->showOnUpdating()->showOnPreview()->fullWidth(),

                    Trix::make(__('Short description'), 'short_description')
                        ->rules('required')
                        ->hideFromIndex()->showOnPreview()->alwaysShow()->withFiles('public')->fullWidth()->stacked(),
                    Trix::make(__('Description'), 'description')
                        ->rules('required')
                        ->hideFromIndex()->showOnPreview()->alwaysShow()->withFiles('public')->fullWidth()->stacked(),

                    Currency::make('Regular price', 'regular_price')
                        ->rules('required', 'numeric', 'between:0,99999999')
                        ->textAlign('right')->help('Valid money value')
                        ->currency(\config('app.app_currency'))->showOnPreview()->fullWidth(),
                    Currency::make('Sale price', 'sale_price')
                        ->rules('required', 'numeric', 'between:0,99999999')
                        ->help('Valid money value')->textAlign('right')
                        ->currency(\config('app.app_currency'))->showOnPreview()->fullWidth(),
                    Boolean::make(__('In stock'), 'in_stock')->fullWidth(),
                    Boolean::make(__('Discount price allowed'), 'discount_price_allowed')->hideFromIndex()->fullWidth(),
                    Boolean::make(__('Is featured'), 'is_featured')->hideFromIndex()->fullWidth(),


                    BelongsTo::make(__('Creator'), 'user', User::class)->hideWhenCreating()->fullWidth(),
                    HasMany::make(name: __('Has discounts'), attribute: 'discounts',
                        resource: \App\Nova\Discount::class)->fullWidth(),
                    HasMany::make(name: __('Has cities'), attribute: 'cities', resource: \App\Nova\City::class)->fullWidth(),


//                    HasOne::make(name: __('Product attributes'), attribute: 'productAttributes', resource: \App\Nova\ProductAttribute::class)->fullWidth(),
                    HasMany::make(name: __('Product attributes'), attribute: 'productAttributes', resource: \App\Nova\ProductAttribute::class)->fullWidth(),

                ]), // Product details Tab


                Tab::make(__('Images'), [
                    Images::make('Product image', 'product') // second parameter is the media collection name
                    ->customPropertiesFields([
                        Boolean::make('Main'),
                        Trix::make(__('Image notes'))
                            ->rules('required')
                            ->withFiles($productHelper->getUploadDirectory())->fullWidth()->stacked(),
                    ])
                    ->conversionOnIndexView($productHelper->getConversionType()) // conversion used to display the image
                    ->conversionOnDetailView($productHelper->getConversionType())
                        ->conversionOnForm($productHelper->getConversionType())
                        ->withResponsiveImages()
                        ->croppable(true)->croppingConfigs(['aspectRatio' => $productHelper->getImageAspectRatio()])
                        ->rules('nullable' /*, 'size:3' */)
                        ->showStatistics(),
                ]), // Images Tab

                Tab::make('Help files', [
                    Audio::make(__('Audio file'), 'audio_help_file'),
                    File::make(__('Pdf help file'), 'pdf_help_file'),
                ]),


                Tab::make(__('Miscellaneous'), [
                    Slug::make(__('Slug'), 'slug')->from('title')
                        ->textAlign('left')->hideFromIndex()->hideWhenCreating()
                        ->readonly()->hideWhenCreating(),

                    Slug::make(__('Sku'), 'sku')
                        ->textAlign('left')->hideFromIndex()->hideWhenCreating()
                        ->readonly()->hideWhenCreating(),

                    Text::make(__('Created at'))->readonly()->resolveUsing(function ($created_at) {
                        return DateConv::getFormattedDateTime($created_at);
                    })->showOnUpdating()->showOnDetail()->hideWhenCreating(),

                    Text::make(__('Updated at'))->readonly()->resolveUsing(function ($updated_at) {
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

                ]), // Miscellaneous Tab


            ]), //->withToolbar()->showTitle(),

        ];
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
        return [new ProductsWithPendingReview, new ActiveProducts];
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
        return [
            new ProductByBrand, new ProductByStatus, new ProductsWithoutStockQty,
            /*            (new Daterangepicker('products.published_at', DateHelper::THIS_MONTH, 'products.title', 'desc'))
                            ->setRanges([
                                'Today' => [Carbon::today(), Carbon::today()],
                                'Yesterday' => [Carbon::yesterday(), Carbon::yesterday()],
                                'This week' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
                                'This month' => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
                                'Last month' => [
                                    Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()
                                ],
                            ])
                            ->setMaxDate(Carbon::today())
                            ->setMinDate(Carbon::today()->startOfYear()),*/

//            new Daterangepicker('users.created_at', DateHelper::THIS_WEEK, 'users.name', 'desc'),
        ];
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
        return [
            new SetStatusDraft,
            new SetStatusPendingReview,
            new SetStatusActive,
            new SetStatusInactive,
            new DownloadExcel,
        ];
    }

    /**
     * Determine if the given resource is authorizable.
     *
     * @return bool
     */
    public static function authorizable()
    {
        return false;
    }
}
