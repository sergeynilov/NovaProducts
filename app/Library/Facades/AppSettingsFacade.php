<?php

namespace App\Library\Facades;

use App\Library\Services\Interfaces\AppSettingsInterface;
use Illuminate\Support\Facades\Facade;

class AppSettingsFacade extends Facade
{
    protected static function getFacadeAccessor() {
        return AppSettingsInterface::class;
    }
}
