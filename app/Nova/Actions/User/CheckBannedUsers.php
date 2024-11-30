<?php

namespace App\Nova\Actions\User;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class CheckBannedUsers extends Action
{
    use InteractsWithQueue, Queueable;

    public $confirmText = 'Information on banned users would be shown';

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
        $html = '<table>';
        $bannedUsers = User::getOnlyBanned()->orderBy('banned_at', 'asc')->get();

        foreach ($bannedUsers as $bannedUser) {
            $html .= '<tr><td>'.
                     '<a href="/nova/resources/users/'.$bannedUser->id.'" target="_blank">'.$bannedUser->name.'('.$bannedUser->email.')'.
                     '</td><td>&nbsp;&nbsp;</td><td>&nbsp;&nbsp;</td><td>'.
                     $bannedUser->id.
                     '</td></tr>';
        }
        $html .= '</table>';

        return Action::modal('modal-response', [
            'title' => 'Information on banned users',
            'html' => htmlspecialchars_decode($html),
        ]);
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
        return [];
    }
}
