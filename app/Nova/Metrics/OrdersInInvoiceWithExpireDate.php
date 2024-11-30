<?php

namespace App\Nova\Metrics;

use App\Enums\NovaSettingsParamEnum;
use App\Library\Facades\DateConv;
use App\Nova\Actions\Order\DeleteOrder;
use Carbon\Carbon;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Metrics\MetricTableRow;
use Laravel\Nova\Metrics\Table;
use App\Models\Order;
use Outl1ne\NovaSettings\NovaSettings;
use Pavloniym\ActionButtons\ActionButton;

// rap2hpoutre/fast-excel

class OrdersInInvoiceWithExpireDate extends Table
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $invoiceOrders = Order::onlyInvoice()->get();
//        \Log::info(varDump($invoiceOrders, ' -1 $invoiceOrders::'));

        $rows = [];
        foreach ($invoiceOrders as $invoiceOrder) {
            $iconClass = '';
            $icon = '';
            $subtitle = '';

            if(empty($invoiceOrder->expires_at)) { // Invoice has no expires at date
                $iconClass = 'text-green-500';
                $icon = 'check-circle';
                $subtitle = $invoiceOrder->id . ' => The invoice has no expire date !' . DateConv::getFormattedDate($invoiceOrder->expires_at);
            }

//            \Log::info(varDump($invoiceOrder->expires_at, ' -1 $invoiceOrder->expires_at::'));
            if(!empty($invoiceOrder->expires_at) and $invoiceOrder->expires_at->isPast()) { // Invoice has no expires at date
                $iconClass = 'text-red-500';
                $icon = 'clock';
                $subtitle = $invoiceOrder->id . ' => The invoice has already expired on ' . DateConv::getFormattedDate($invoiceOrder->expires_at);
            }

            if(!empty($invoiceOrder->expires_at) and !$invoiceOrder->expires_at->isPast()) { // Invoice has no expires at date
                $iconClass = 'text-yellow-500';
                $icon = 'exclamation';
                $now = Carbon::now();
                $difference = ($invoiceOrder->expires_at->diff($now)->days < 1)
                    ? 'today'
                    : $invoiceOrder->expires_at->diffForHumans($now);
                $subtitle = $invoiceOrder->id . ' => The invoice will expire ' . $difference . ' today';
            }

            $rows[] = MetricTableRow::make()
                ->icon($icon)
                ->iconClass($iconClass)
                ->title($invoiceOrder->order_number)
                ->subtitle($subtitle)

                ->actions(function () use($invoiceOrder) {
                    return [
                        MenuItem::make('Edit', '/resources/orders/' . $invoiceOrder->id),
                        ActionButton::make('Delete')
                            ->action(new DeleteOrder(), $invoiceOrder->id),
                    ];
                });

        }
        return $rows;
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|null
     */
    public function cacheFor()
    {
        return now()->addMinutes(NovaSettings::getSetting(NovaSettingsParamEnum::METRIX_CACHING_IN_MINUTES->value));
    }
}
