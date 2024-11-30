<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
//use Anaseqal\NovaImport\Actions\Action;
//use Anaseqal\NovaImport\NovaImport;


use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Http\Requests\NovaRequest;
use Maatwebsite\Excel\Excel;

class ImportProducts extends Action
{
    use InteractsWithQueue, Queueable;

    public function name()
    {
        return __('Import products from xls file');
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
//        Excel::import(new \App\Imports\ImportProducts, $fields->file);
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            File::make('File')
            ->rules('required'),
        ];
    }

    /* Declaration of App\Nova\Actions\ImportProducts::fields(Laravel\Nova\Http\Requests\NovaRequest $request) must be compatible with Anaseqal\NovaImport\Actions\Action::fields() */
}
