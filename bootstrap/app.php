<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);


define('ProductsFactoryCount', 10);
if ( ! defined("ACCESS_PERMISSION_ADMIN")) {  // can do all
    define("ACCESS_PERMISSION_ADMIN", 1);  // Admin
}
if ( ! defined("ACCESS_PERMISSION_ADMIN_LABEL")) {
    define("ACCESS_PERMISSION_ADMIN_LABEL", 'Admin');
}


if ( ! defined("ACCESS_PERMISSION_MANAGER")) {  // Manager - can  edit pages
    define("ACCESS_PERMISSION_MANAGER", 2);  // Manager
}
if ( ! defined("ACCESS_PERMISSION_MANAGER_LABEL")) {
    define("ACCESS_PERMISSION_MANAGER_LABEL", 'Manager');
}


if ( ! defined("ACCESS_PERMISSION_SALESPERSON")) {  // SALESPERSON - can work with orders
    define("ACCESS_PERMISSION_SALESPERSON", 3); // Salesperson
}
if ( ! defined("ACCESS_PERMISSION_SALESPERSON_LABEL")) {
    define("ACCESS_PERMISSION_SALESPERSON_LABEL", 'Sales person');
}

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
