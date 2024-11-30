<?php

namespace App\Nova\Actions\User;

use App\Enums\ActionNoteTypeEnum;
use App\Enums\ConfigValueEnum;
use App\Library\Facades\LoggedUserFacade;
use App\Library\Services\SendEmailToUser;
use App\Models\ActionNote;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Database\QueryException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Actions\ActionResponse;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class ChangeUserPassword extends Action
{
    use InteractsWithQueue, Queueable;

    public $confirmText = 'New password would be generated and sent to the user\'s email';

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
        $user = $models[0];
        try {
            $newPassword = \Str::password(8);
            $body = 'Your new password is : '.$newPassword;
            \Log::info(varDump($body, ' -1 $body::'));
            $appName = ConfigValueEnum::get(ConfigValueEnum::APP_NAME);

            DB::beginTransaction();

            $user->password = Hash::make($newPassword);
            $user->updated_at = Carbon::now(ConfigValueEnum::get(ConfigValueEnum::TIMEZONE));
            $user->save();
            if ( (new SendEmailToUser)->send(user: $user, title: 'Your password at '.$appName.' was updated !',
                body: $body)) {
                ActionNote::create([
                    'user_id' => LoggedUserFacade::getLoggedUserId(),
                    'model_type' => User::class,
                    'model_id' => $user->id,
                    'note_type' => ActionNoteTypeEnum::SET_USER_NEW_PASSWORD,
                    'note' => $fields['note'],
                ]);
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

        return ActionResponse::message('Email is generated and sent to the user\'s email !');
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
