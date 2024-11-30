<?php

namespace App\Providers;

use App\Enums\ConfigValueEnum;
use App\Enums\IconEnum;
use App\Enums\NovaSettingsParamEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\ProductStatusEnum;
use App\Enums\UserStatusEnum;
use App\Library\Facades\AppSettingsFacade;
use App\Models\City as CityModel;
use App\Models\Brand as BrandModel;
use App\Nova\Brand;
use App\Nova\Category;
use App\Models\Category as CategoryModel;
use App\Nova\City;
use App\Nova\Discount;
use App\Models\Discount as DiscountModel;
use App\Nova\Reports\CompletedOrders;
use App\Nova\Test;
use App\Models\User as UserModel;
use App\Models\Order as OrderModel;
use App\Models\Product as ProductModel;
use Illuminate\Support\Facades\App;
use App\Nova\Dashboards\Main;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\Number;
//use Laravel\Nova\Fields\Repeater;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Illuminate\Http\Request;
use Wdelfuego\NovaWizard\NovaWizard;
use Outl1ne\NovaSimpleRepeatable\SimpleRepeatable;
use Oneduo\NovaFileManager\NovaFileManager;

use Outl1ne\NovaSettings\NovaSettings;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;

//outl1ne/nova-settings
//use Outl1ne\NovaSettings\NovaSettings;
//use Timothyasp\Badge\Badge;


class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Nova::withBreadcrumbs();

        \Outl1ne\NovaSettings\NovaSettings::addSettingsFields([
            Boolean::make(NovaSettingsParamEnum::USER_ACTIVE_ON_REGISTER->value,
                fn() => $this->resolveUserActiveOnRegisterValue()),
            Text::make(NovaSettingsParamEnum::CONTACT_US_EMAIL->value, fn() => $this->resolveContactUsEmailValue()),
            Number::make(NovaSettingsParamEnum::INVOICE_DAYS_BEFORE_EXPIRE->value,
                fn() => $this->resolveInvoiceDaysBeforeExpireValue()),

            Number::make(NovaSettingsParamEnum::METRIX_CACHING_IN_MINUTES->value,
                fn() => $this->resolveMetrixCachingInMinutesValue()),


            SimpleRepeatable::make(__('App langs'), NovaSettingsParamEnum::APP_LANGS->value, [
                Text::make(__('Lang code'), 'lang_code'),
                Text::make(__('Lang name'), 'lang_name'),
                Boolean::make(__('Is default'), 'is_default')->default(false),
            ])
                ->canAddRows(true) // Optional, true by default
                ->canDeleteRows(true), // Optional, true by default


/*//            Repeater::make('Categories in subscription', 'categories_in_subscription')
            Repeater::make('Categories in subscription', 'value')
                ->repeatables([
                    \App\Nova\Repeater\CategoriesInSubscription::make(),
                ])
                ->asJson()*/


        ],
            [
                NovaSettingsParamEnum::USER_ACTIVE_ON_REGISTER->value => 'boolean',
                NovaSettingsParamEnum::CONTACT_US_EMAIL->value => 'string',
                NovaSettingsParamEnum::INVOICE_DAYS_BEFORE_EXPIRE->value => 'integer',
                NovaSettingsParamEnum::METRIX_CACHING_IN_MINUTES->value => 'integer',
                NovaSettingsParamEnum::APP_LANGS->value => 'array',
            ],
            'general'
        );
        $this->fillAppMenu();
    }

    public function resolveUserActiveOnRegisterValue(): bool
    {
        $value = NovaSettings::getSetting(NovaSettingsParamEnum::USER_ACTIVE_ON_REGISTER->value, 0);

        return $value !== null ? boolval($value) : true; // true is the default value
    }

    public function resolveContactUsEmailValue(): string
    {
        return NovaSettings::getSetting(NovaSettingsParamEnum::CONTACT_US_EMAIL->value, 'noemail');
    }

    public function resolveInvoiceDaysBeforeExpireValue(): int
    {
        return NovaSettings::getSetting(NovaSettingsParamEnum::INVOICE_DAYS_BEFORE_EXPIRE->value, 30);
    }

    public function resolveMetrixCachingInMinutesValue(): int
    {
        return NovaSettings::getSetting(NovaSettingsParamEnum::METRIX_CACHING_IN_MINUTES->value, 30);
    }

    private function getFooterContent(): void
    {
        Nova::footer(function ($request) {
            return Blade::render('nova/footer');
        });
    }

    private function fillAppMenu()
    {
        Nova::mainMenu(function (Request $request) {
            return [
                MenuSection::dashboard(Main::class)
                    ->icon(IconEnum::Dashboard->value),


/*                Nova::userMenu(function (Request $request, Menu $menu) {
                    $menu->prepend(
                        MenuItem::make(
                            'My Profile',
                            "/resources/users/{$request->user()?->getKey()}"
                        ),
                    );

                    return $menu;
                });*/

                MenuSection::make('Products', [
                    MenuItem::make('Products', '/resources/products'),
                    MenuItem::make('Create Product', '/resources/products/new'),
                ])->icon(IconEnum::Product->value)->collapsable()
                    ->withBadgeIf(function () {
                        return ProductModel::getByStatus(ProductStatusEnum::ACTIVE)->count();
                    }, 'info', function () {
                        return ProductModel::getByStatus(ProductStatusEnum::ACTIVE)->count() > 0;
                    }),

                Menusection::resource(Brand::class)->icon(IconEnum::Brand->value)
                    ->withBadgeIf(function () {
                        return BrandModel::getOnlyActive()->count();
                    }, 'info', function () {
                        return BrandModel::getOnlyActive()->count() > 0;
                    }),

                Menusection::resource(Discount::class)->icon(IconEnum::Discount->value)
                    ->withBadgeIf(function () {
                        return DiscountModel::getOnlyActive()->count();
                    }, 'info', function () {
                        return DiscountModel::getOnlyActive()->count() > 0;
                    }),

                Menusection::resource(Category::class)->icon(IconEnum::Category->value)
                    ->withBadgeIf(function () {
                        return CategoryModel::getOnlyActive()->count();
                    }, 'info', function () {
                        return CategoryModel::getOnlyActive()->count() > 0;
                    }),

                Menusection::resource(City::class)->icon(IconEnum::City->value)
                    ->withBadgeIf(function () {
                        return CityModel::count();
                    }, 'info', function () {
                        return 2;
                    }),

                MenuSection::make('Orders', [
                    MenuItem::make('All orders', '/resources/orders'),
                    MenuItem::make('Create order', '/resources/orders/new')
                        ->canSee(function (NovaRequest $request) {
                            return true; //$request->user()->is_admin;
                            // NSN_TODO
                        }),
                ])->icon(IconEnum::Orders->value)->collapsable()
                    ->withBadgeIf(function () {
                        return OrderModel::onlyProcessing()->count();
                    }, 'info', function () {
                        return OrderModel::onlyProcessing()->count() > 0;
                    }),   // Orders MENU BLOCK END


                MenuSection::make('Users', [
                    MenuItem::make('All Users', '/resources/users'),
                    MenuItem::make('Create User', '/resources/users/new')
                        ->canSee(function (NovaRequest $request) {
                            return true; //$request->user()->is_admin;
                            // NSN_TODO
                        }),
                    MenuItem::link('Register staff user', NovaWizard::pathToWizard('register-staff-user'))
                ])->icon(IconEnum::Users->value)->collapsable()
                    ->withBadgeIf(function () {
                        return UserModel::getByStatus(UserStatusEnum::ACTIVE)->count();
                    }, 'info', function () {
                        return UserModel::getByStatus(UserStatusEnum::ACTIVE)->count() > 0;
                    }),   // users MENU BLOCK END


                MenuSection::make('Reports', [
                    MenuItem::resource(CompletedOrders::class),
                ]),   // REPORTS MENU BLOCK END

                MenuSection::make('Testing', [
                    MenuItem::resource(Test::class),
                ]),   // REPORTS MENU BLOCK END

                MenuSection::make('Administration', [
                    MenuItem::make('Site settings', '/nova-settings/general'),
                    MenuItem::make('App logs', '/logs'),
                    MenuItem::make('File manager', '/nova-file-manager'),
                    MenuItem::make('Unsplash images', '/unsplash-images'),
                ])->icon(IconEnum::UnsplashImages->value)->collapsable(),

                MenuSection::make('Page manager', [
                    MenuItem::make('Pages', '/resources/pages'),
                    MenuItem::make('Regions', '/resources/regions'),
                ])->icon(IconEnum::PageManager->value)->collapsable(),


                MenuItem::externalLink('Nova docs', 'https://nova.laravel.com/docs')->openInNewTab()

            ];
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email,
                Str::of(ConfigValueEnum::get(ConfigValueEnum::NOVA_ALLOWED_USERS))->explode(';')->toArray()
            );
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new \Outl1ne\NovaSettings\NovaSettings,
            new NovaWizard('register-staff-user'),
            \Laravel\Nova\LogViewer\LogViewer::make(),

            NovaFileManager::make(),

            new \Outl1ne\PageManager\PageManager(),
//            new NovaImport,

//          ->withSeoFields(fn () => []) // Optional

        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        \Laravel\Nova\Nova::$initialPath = '/resources/users';
//        \Laravel\Nova\Nova::$initialPath = '/resources/products/new';
    }
}
