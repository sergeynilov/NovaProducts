<?php

namespace App\Console\Commands\Admin\Reports;

use App\Enums\ConfigValueEnum;
use App\Library\Services\Product\ProductsPublishedByPeriod;
use Carbon\Carbon;use Illuminate\Console\Command;
use Illuminate\Contracts\Database\Eloquent\Builder;

/* php artisan app:products-daily-published
*/
class ProductsDailyPublished extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:products-daily-published';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = Carbon::now(ConfigValueEnum::get(ConfigValueEnum::TIMEZONE));
        $startDate = $date->copy()->startOfDay();
        $endDate = $date->copy()->endOfDay();

        $productsPublishedByPeriod = new ProductsPublishedByPeriod();
        $ret = $productsPublishedByPeriod->get( dateFrom: $startDate, dateTill: $endDate, periodTitle: 'today');

        echo count($ret).'::$ret::'.print_r($ret,true);
    }
}
