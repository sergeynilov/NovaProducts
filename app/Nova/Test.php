<?php

namespace App\Nova;

use App\Models\Permission;
use Creode\CollapsibleRadios\Field\CollapsibleRadios;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

use Outl1ne\NovaSimpleRepeatable\SimpleRepeatable;

//use Creode\CollapseRadios\Field\CollapsibleRadios;

/* http://local-nova-products.com/nova/resources/tests */

class Test extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Permission>
     */
    public static $model = \App\Models\Permission::class;

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
    public static $search
        = [
            'id',
        ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        $permissionOptions = Permission::get()->toArray();

        \Log::info(varDump($permissionOptions, ' -1 $permissionOptions::'));
        foreach ($permissionOptions as $permissionOption) {
            $mappedPermissionOptions[$permissionOption['id']] = $permissionOption['name'];
        }

//        $mappedPermissionOptions = Arr::map($permissionOptions, function ($value, $key) {
//            return [$value['id'] => $value['name']];
//        });

//        \Log::info(varDump($mappedPermissionOptions, ' -1 mappedPermissionOptions::'));

        return [

            CollapsibleRadios::make('Model', 'model_id')
                ->options([
                    [
                        'label' => 'Option 1',
                        'value' => 1,
                        'id' => 1,
                        'parent_id' => null,
                    ],
                    [
                        'label' => 'Option 2',
                        'value' => 2,
                        'id' => 2,
                        'parent_id' => 1,
                    ],
                    [
                        'label' => 'Option 3',
                        'value' => 3,
                        'id' => 3,
                        'parent_id' => 2,
                    ]
                ])
                ->nullable()
                ->rules('required'),

            ID::make()->sortable(),

            SimpleRepeatable::make('Users', 'users', [
                Text::make('First name'),
                Text::make('Last name'),
                Email::make('Email'),
            ])
                ->canAddRows(true) // Optional, true by default
                ->canDeleteRows(true), // Optional, true by default
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
        return [];
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
