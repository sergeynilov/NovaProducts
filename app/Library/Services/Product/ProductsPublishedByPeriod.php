<?php

namespace App\Library\Services\Product;

use App\Enums\IconEnum;
use App\Enums\ProductStatusEnum;
use App\Models\ModelHasPermission;
use App\Models\Product;
use Carbon\Carbon;
use Laravel\Nova\Notifications\NovaNotification;

class ProductsPublishedByPeriod
{
    public function get(Carbon $dateFrom, Carbon $dateTill, string $periodTitle): array
    {
        $publishedProducts = Product::getByStatus(ProductStatusEnum::ACTIVE)->getPublishedAtBetween($dateFrom,
            $dateTill)->get();

        $managers = ModelHasPermission
            ::getByPermissionId(ACCESS_PERMISSION_MANAGER)
            ->with('user')
            ->get();
        $sentToManagers = 0;
        if (count($publishedProducts) > 0) { // Some published products
            $html = '';
            foreach ($publishedProducts as $product) {
                $html .= $product->id.', ';
            }

            foreach ($managers as $manager) {
                /* Send notification to all managers of the site */
                if ( ! empty($manager->user)) {
                    $manager->user->notify(
                        NovaNotification::make()
                            ->message('For '.$periodTitle.' '.count($publishedProducts).' product(s) were published : '.$html)
                            ->action('View', '/nova/resources/products/')
                            ->icon(IconEnum::Notification->value)
                            ->type('info')
                    );
                    $sentToManagers++;
                }
            }
        }

        return ['publishedProducts' => count($publishedProducts), 'sentToManagers' => $sentToManagers];
    }
}
