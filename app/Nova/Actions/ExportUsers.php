<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\User;

class ExportUsers extends Action
{
    use InteractsWithQueue, Queueable;

    public $confirmText = 'Data with selected users would be imported into export into filw';

//    public function name(): string
//    {
//        \Log::info(varDump(-1, ' -1 ExportUsers->name::'));
//        return __('Export Users');
//    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        // Do work to export records

        \Log::info(varDump($models , ' -1 handle ExportUsers $models::'));
//        (new FastExcel($models))->export('tmp/users.xlsx');

        return (new FastExcel($models))->download('users.xlsx');
//        return FastExcel::data($models)->export('users.xlsx');

//        file:///mnt/_work_sdb8/wwwroot/lar/NovaProducts/public/robots.txt

        return DetachedAction::message('Users successfully exported !');
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        \Log::info( '-1 fields $request->all()::' . print_r( $request->all(), true  ) );

        return [];
    }
}
