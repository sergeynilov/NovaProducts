<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Simonbarrettact\Unsplash\Unsplash;

//use SimonBarrettACT\Unsplash\Unsplash;

class UnsplashImage extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\UnsplashImage>
     */
    public static $model = \App\Models\UnsplashImage::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title',
    ];

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
            Text::make(__('Title'), 'title')->sortable()
                ->textAlign('left')->placeholder('Product Title')
                ->rules('required', 'max:255')->sortable()
                ->updateRules('unique:unsplash_images,title,{{resourceId}}')
                ->creationRules('unique:unsplash_images,title')->fullWidth(),

            Slug::make(__('Slug'), 'slug')->from('title')
                ->textAlign('left')->hideFromIndex()->hideWhenCreating()
                ->readonly()->hideWhenCreating(),

            Boolean::make(__('Featured'), 'featured')->sortable()->fullWidth(),

            Unsplash::make('Photo', 'unsplash_id'),


            /*         Schema::create('unsplash_images', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->boolean('featured')->default(false);

            $table->string('unsplash_id', 20);
            $table->timestamps();
        });
 */
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
