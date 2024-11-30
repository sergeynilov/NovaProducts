<?php

namespace App\Nova\Dashboards;

use App\Enums\ConfigValueEnum;
use App\Nova\Lenses\Orders\MostActiveUsersWithProcessingOrders;
use App\Nova\Metrics\NewReleases;
use App\Nova\Metrics\OrdersByStatusPieChart;
use App\Nova\Metrics\OrdersCompleted;
use App\Nova\Metrics\OrdersCompletedByManagerByDays;
use App\Nova\Metrics\OrdersInInvoiceWithExpireDate;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
//            new OrdersInInvoiceWithExpireDate,
//
//            new OrdersByStatusPieChart, new OrdersCompleted, new OrdersCompletedByManagerByDays,
//            new NewReleases,
//

//            new MostActiveUsersWithProcessingOrders,

            new Help,
        ];
    }

    public function name()
    {
        return ConfigValueEnum::get(ConfigValueEnum::APP_NAME) . ': Dashboard';
    }


    public function lenses()
    {
        return [
            new MostActiveUsersWithProcessingOrders,
        ];
    }

}
