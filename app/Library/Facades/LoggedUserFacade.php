<?php

namespace App\Library\Facades;

use App\Library\Services\Interfaces\LoggedUserInterface;
use Illuminate\Support\Facades\Facade;

class LoggedUserFacade extends Facade
{
    protected static function getFacadeAccessor() {
        return LoggedUserInterface::class;
    }
}
