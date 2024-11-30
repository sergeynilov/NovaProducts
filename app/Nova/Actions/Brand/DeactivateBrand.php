<?php

namespace App\Nova\Actions\Brand;

use App\Library\Services\Brand\ChangeActive;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Database\QueryException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class DeactivateBrand extends Action
{
    use InteractsWithQueue, Queueable;

    public $confirmText = 'Deactivated brand could be used on frontend part';

    protected User $user;
    protected ChangeActive $changeActive;

    /**
     * @param  User|null  $user
     */
    public function __construct(User $user = null)
    {
        if(!$user) {
            $user = auth()->user();
        }
        $this->user = $user;
        $this->changeActive = new changeActive($user);
    }

    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name()
    {
        return 'Brand deactivation';
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
        $errorMsg = '';
        $exception = null;
        $brandIds = $models->pluck('id');
        \Log::info(varDump($brandIds, ' DeactivateBrand -1 $brandIds::'));
        $deactivatedBrandsCount = 0;
        try {
            DB::beginTransaction();
            foreach ($brandIds as $brandId) {
                if($this->changeActive->setInactive($brandId, note: $fields->note)) {
                    $deactivatedBrandsCount++;
                }
           }
            DB::commit();
        } catch (QueryException|\Exception $e) {
            DB::rollBack();
            \Log::info($e->getMessage());
            $exception = $e;
        }

        if ($exception) {
            return ActionResponse::danger($errorMsg.': '.$exception->getMessage());
        }

        return ActionResponse::message('Selected '.$deactivatedBrandsCount.' brand(s) deactivated!');
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
            Text::make(__('Note')),
        ];
    }
}
