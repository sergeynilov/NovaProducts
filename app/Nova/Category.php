<?php

namespace App\Nova;

use App\Enums\CategoryActiveEnum;
use App\Library\Facades\DateConv;
use App\Nova\Filters\CategoryByActive;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Query\Search\SearchableText;

class Category extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Category>
     */
    public static $model = \App\Models\Category::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
//    public static $title = 'name';

    /**
     * Get the value that should be displayed as TITLE to represent the resource(in global search).
     *
     * @return string
     */
    public function title()
    {
        $childCategoriesText = '';
        $subcategoriesCount = \App\Models\Category::getByParentId($this->id)->count() ;
        if( $subcategoriesCount > 0) {
            $childCategoriesText .= '. With ' . $subcategoriesCount . ' subcategory(ies) ';
        }
        return $this->name . $childCategoriesText;
    }

    /**
     * Get the value that should be displayed as SUBTITLE to represent the resource(in global search).
     *
     * @return string
     */
    public function subtitle()
    {
        return "Status: ".CategoryActiveEnum::getLabel($this->active);
    }

    /* CREATE TABLE `categories` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` smallint unsigned DEFAULT NULL,
        `active` tinyint(1) NOT NULL DEFAULT '0',
  `slug` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`), */

    /**
     * The columns that should be searched.
     *
     * @var array
     */
//    public static $search = [ 'name', 'description' ];
    public static function searchableColumns()
    {
        return ['id', new SearchableText('name'), new SearchableText('description')];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->/*withCount('products')->*/with('parent');
        return parent::indexQuery($request, $query);
    }

    /**
     * Get the fields displayed by listing of the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     *
     * @return array
     */
    public function fieldsForIndex(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),


            Text::make(__('Name'), function(){
                if($this->parent) {
                    return "{$this->parent->name} / {$this->name}";
                }
                return $this->name;
            }),


            Text::make('Created at')->readonly()->resolveUsing(function ($created_at) {
                return DateConv::getFormattedDateTime($created_at);
            }),

            Text::make('Updated at')->readonly()->resolveUsing(function ($updated_at) {
                return DateConv::getFormattedDateTime($updated_at);
            })->showOnIndex( // in "List" mode "categories"
                function (NovaRequest $request, $resource) {
                    return ! empty($updated_at);
                }
            )->sortable(),

//            Text::make('Name'),
            // Add other fields you want to display on the index view
        ];
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
            ID::make(),
            Select::make(__('Parent category'), 'parent_id')
                ->options(Category::getByParentId(parentId: null)->get()->pluck('name', 'id'))
                ->rules('nullable')
                ->showOnCreating()->showOnUpdating()->hideFromDetail()->hideFromIndex(),

            Text::make(__('Parent category'), 'category_parent_id')
                ->resolveUsing(function () {
//                    \Log::info(varDump($this->parent_id, ' -1 $this->parent_id::'));
                    if(empty($this->parent_id)) return '';
                    $category = Category::find($this->parent_id);
                    return $category->name ?? '';
                })->showOnDetail()->hideWhenCreating()->hideWhenUpdating(),


            Text::make(__('Name'), 'name')
                ->rules('required', 'max:50')
                ->updateRules('unique:categories,name,{{resourceId}}')
                ->creationRules('unique:categories,name')
                ->textAlign('left')->placeholder('Category name'),

            Boolean::make(__('Active'), 'active')->rules('nullable'),

            Slug::make('slug')
                ->textAlign('left')->/*hideFromIndex()->*/hideWhenCreating()
                ->readonly()->hideWhenCreating(),

//            Text::make(__('Products count'), function () {
//                return $this->resource->products()->count();
//            })->textAlign('right')->readonly(), //->showOnDetail()->showOnUpdating()->showOnPreview(),


            Trix::make(__('Description'))
                ->rules('required')
                /*->hideFromIndex()*/->showOnPreview()->alwaysShow()->withFiles('public')->fullWidth()->stacked(),

            Text::make('Created at')->readonly()->resolveUsing(function ($created_at) {
                return DateConv::getFormattedDateTime($created_at);
            })->showOnUpdating()->showOnDetail()->hideWhenCreating(),

            Text::make('Updated at')->readonly()->resolveUsing(function ($updated_at) {
                return DateConv::getFormattedDateTime($updated_at);
            })->showOnUpdating()->hideWhenCreating()->showOnDetail( // in "View" mode "categories/11"
                function (NovaRequest $request, $resource) {
                    return ! empty($updated_at);
                }
            )->showOnUpdating( // in "Edit" mode "categories/11/edit"
                function (NovaRequest $request, $resource) {
                    return ! empty($updated_at);
                }
            )->showOnIndex( // in "List" mode "categories"
                function (NovaRequest $request, $resource) {
                    return ! empty($updated_at);
                }
            ),

//            HasMany::make(name: __('Has products'), attribute:  'products', resource: \App\Nova\Product::class),


            HasMany::make(name: __('Has products'), attribute:  'products', resource: \App\Nova\Product::class),
//            BelongsTo::make(name: __('Has products'), attribute:  'products', resource: \App\Nova\Product::class),
//            BelongsToMany::make(name: __('Has products'), attribute:  'products', resource: \App\Nova\Product::class),
//            HasMany::make(name: __('Has products'), attribute:  'products', resource: \App\Nova\Product::class),

        ];
    }

    /* CREATE TABLE `categories` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` smallint unsigned DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `slug` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL, */

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
        return [new CategoryByActive];
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
