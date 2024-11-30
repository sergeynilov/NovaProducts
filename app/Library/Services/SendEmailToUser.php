<?php

namespace App\Library\Services;

use App\Enums\NovaSettingsParamEnum;

use App\Models\User;
use Carbon\Carbon;

/**
 * Class to wrap AppSettings service
 */
class SendEmailToUser
{
    public static function send(User $user, string $title, string $body): bool
    {
        \Log::info(varDump($user, ' -1 $user::'));
        \Log::info(varDump($title, ' -1 title::'));
        \Log::info(varDump($body, ' -1 $body::'));
        /* TODO LATER WITH EMAILING SERVER */
        return true;
    }

}
