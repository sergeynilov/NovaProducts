<?php

namespace App\Providers;

use App\Library\Services\Interfaces\AppSettingsInterface;
use App\Library\Services\Interfaces\LoggedUserInterface;
use App\Library\Services\LoggedUserService;
use App\Library\Services\AppSettingsService;
use App\Library\Services\SqlDebug;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Database\Events\TransactionRolledBack;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
        $this->app->bind(LoggedUserInterface::class,LoggedUserService::class);  // DONE
        $this->app->bind(AppSettingsInterface::class,AppSettingsService::class);
/*        $this->app->bind(UserCheckPermissionInterface::class,UserCheckPermission::class);  // DONE */

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('local') or $this->app->environment('testing')) {
            $sqlDebug = App::make(SqlDebug::class);
            \Event::listen(
                [
                    TransactionBeginning::class,
                ],
                function ($event) use ($sqlDebug) {
                    $sqlDebug->writeSqlStatement("  BEGIN; ", null, null, true);

                }
            );


            \Event::listen(
                [
                    TransactionCommitted::class,
                ],
                function ($event) use ($sqlDebug) {
                    $sqlDebug->writeSqlStatement("  COMMIT; ", null, null, true);
                }
            );


            \Event::listen(
                [
                    TransactionRolledBack::class,
                ],
                function ($event) use ($sqlDebug) {
                    $sqlDebug->writeSqlStatement("  ROLLBACK; ", null, null, true);
                }
            );


            \DB::listen(function ($query) use ($sqlDebug) {
                $bindings = [];
                foreach ($query->bindings as $binding) {
                    if ($binding instanceof \DateTime) {
                        $bindings[] = $binding->format('Y-m-d H:i:s');
                        continue;
                    }
                    $bindings[] = $binding;
                }

                $str = $query->sql;
                $sqlDebug->writeSqlStatement($str, $query->time, $bindings, false);
            });

        } // if ($this->app->environment('local') or $this->app->environment('testing')) {
    }
}
