<?php

namespace App\Nova\Actions\Product;

use App\Library\Services\Product\ChangeStatus;
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

class SetStatusDraft extends Action
{
    use InteractsWithQueue, Queueable;

    protected ChangeStatus $changeStatus;

    public $confirmText = 'Setting user\'s status into "Draft" you forbid the user to enter into his account';

    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name()
    {
        return 'Setting status Draft';
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
        $this->changeStatus = new ChangeStatus($user);
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
        $productIds = $models->pluck('id');
//        \Log::info(varDump($productIds, ' -1 $productIds::'));
        $productsSetToDraftCount = 0;
        try {
            DB::beginTransaction();
            foreach ($productIds as $productId) {
                if($this->changeStatus->setDraft(productId: $productId, note: $fields->note)) {
                    $productsSetToDraftCount++;
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

        return ActionResponse::message('Selected '.$productsSetToDraftCount.' product(s) were set to draft !');
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
