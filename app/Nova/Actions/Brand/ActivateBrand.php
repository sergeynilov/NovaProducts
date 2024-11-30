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

class ActivateBrand extends Action
{
    use InteractsWithQueue, Queueable;

    protected User $user;
    protected ChangeActive $changeActive;

    public $confirmText = 'Activated brand could be used on frontend part';

    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name()
    {
        return 'Brand activation';
    }

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
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $errorMsg = '';
        $exception = null;
        $brandIds = $models->pluck('id');
//        \Log::info(varDump($brandIds, ' -1 $brandIds::'));
        $activatedBrandsCount = 0;
        try {
            DB::beginTransaction();
            foreach ($brandIds as $brandId) {
                if($this->changeActive->setActive(brandId: $brandId, note: $fields->note)) {
                    $activatedBrandsCount++;
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

        return ActionResponse::message('Selected '.$activatedBrandsCount.' brand(s) activated!');
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Text::make(__('Note')),
        ];
    }
}
