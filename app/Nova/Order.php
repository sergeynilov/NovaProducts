<?php

namespace App\Nova;

use App\Enums\OrderStatusEnum;
use App\Library\Facades\DateConv;
use App\Nova\Helpers\OrderHelper;
use App\Nova\Lenses\Orders\MostActiveUsersWithProcessingOrders;
use DigitalCreative\ColumnToggler\ColumnTogglerTrait;
use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\Traits\HasTabs;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Query\Search\SearchableText;
use Timothyasp\Badge\Badge;

class Order extends Resource
{
    use ColumnTogglerTrait;
    use HasTabs;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Order>
     */
    public static $model = \App\Models\Order::class;

    /**
     * Get the value that should be displayed as title to represent the resource(in global search).
     *
     * @return string
     */
//    public function title()
//    {
//        return $this->order_number . '. With status ' . OrderStatusEnum::getLabel($this->status);
//    }

//    public function subtitle()
//    {
//        return "Postal code: " . $this->postal_code . ', Region: ' . $this->region;
//    }

    /* -- NovaProducts.orders definition

CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `creator_id` bigint unsigned NOT NULL,
  `billing_first_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_last_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_company` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_country` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_address` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_address2` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_postcode` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `info` mediumtext COLLATE utf8mb4_unicode_ci,
  `price_summary` int unsigned NOT NULL COMMENT 'Cast on client must be used - Money sum = value/ 100',
  `items_quality` smallint unsigned NOT NULL,
  `payment` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('D','I','C','P','O','R') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'D - Draft, I-Invoice, C-Cancelled, P-Processing, O - Completed, R - Refunded',
  `order_number` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `other_shipping` tinyint(1) NOT NULL,
  `payment_client_ip` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_operation_date` date DEFAULT NULL,
  `expires_at` date DEFAULT NULL,
  `mode` enum('T','L') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'T - , L -',
  `manager_id` bigint unsigned NOT NULL,
  `completed_by_manager_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL, */

//    public function subtitle()
//    {
//        return "Status: {".OrderStatusEnum::getLabel($this->order_number)."}";
//    }

//    public static function label() {
//        return 'Custom order';
//    }

    public static $globalSearchResults = 10;

//    public static $tableStyle = 'tight';
    public static $showColumnBorders = false;
    public static $perPageOptions = [10, 25, 100];

    /**
     * The action used for the click on the table row. Available options are 'view', 'select' and 'update'.
     *
     * @var string
     */
    /**
     * The columns that should be searched.
     *
     * @var array
     */
/*    public static $search = ['order_number'];

        Schema::table('orders', function (Blueprint $table) {
                  $table->fullText('billing_email' );
                    $table->fullText('billing_country' );
                    $table->fullText('' );
                    $table->fullText('' );
            $table->fullText('' );
            $table->fullText('' );
            $table->fullText('' );
            $table->fullText('' );
        });

      */

/*    public static function searchableColumns()
    {
        return ['id', new SearchableText('billing_company'),
            new SearchableText('billing_phone'), new SearchableText('billing_email'),
            new SearchableText('billing_country'), new SearchableText('billing_address'),
            new SearchableText('billing_address2'), new SearchableText('billing_city'),
            new SearchableText('billing_postcode'), new SearchableText('info'),
            new SearchableText('order_number')];
    }
*/

    protected OrderHelper $orderHelper;

    public function __construct()
    {
        parent::__construct();
        $this->orderHelper = new OrderHelper();
    }

    /**
     * Get the fields displayed by listing ofthe resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     *
     * @return array
     */
    public function fieldsForIndex(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Payment'), 'payment')->rules('max:2'),
            Text::make(__('Order number'), 'order_number')->rules('max:15'),
        ];
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
//        $productHelper = new OrderHelper();
//        [$isEditor, $isCreate, $headingBgColor, $editorTitle, $status] = $productHelper->getEditorProps($request);

        return [
            ID::make()->sortable(),
            Text::make(__('Payment'), 'payment')->rules('max:2'),
            Text::make(__('Order number'), 'order_number')->rules('max:15'),

        ];
//        \Log::info(' -1 $request::');
//        \Log::info(json_encode($request));
//        $isEdit = ! empty($this->id);
//        \Log::info(' -13 fields $isEdit::');
//        \Log::info(json_encode($isEdit));
//        $editorTitle = 'Select status manually';
//
//        $otherShipping = $this->other_shipping ?? false;
//        \Log::info(' -141 $otherShipping::');
//        \Log::info(json_encode($otherShipping));
//
//        $status = $this->status ?? null;
//        \Log::info(' -145 $status::');
//        \Log::info(json_encode($status));
//        $statusOptions = OrderStatusEnum::getStatusSelectionItems();
//        $headingBgColor = 'text-white-100';
//        if ($isEdit) {
//            $headingBgColor = OrderStatusEnum::getStatusColors(hexValue: false)[$status->value];
//            $editorTitle = ' With "'.OrderStatusEnum::getLabel($this->status).'" status use actions to change status';
//        }

        return [
            Heading::make('<p class="text-xl font-bold '.$headingBgColor.'">'.$editorTitle.'</p>')->asHtml(),
            Tabs::make('Order editor', [
                Tab::make('Details', [
                    ID::make()->sortable(),

/*                    BelongsTo::make(name:'Creator', attribute:'creator', resource:User::class),
                    Text::make(__('Payment'), 'payment')->rules('max:2')->hideFromIndex(),
                    Text::make(__('Order number'), 'order_number')->rules('max:15'),

                    Badge::make(__('Status'))->required()
                        ->options($statusOptions)->hideFromDetail()->hideWhenUpdating()
                        ->colors(OrderStatusEnum::getStatusColors(hexValue: true))->displayUsingLabels(),

                    Boolean::make(__('Other shipping'), 'other_shipping')->hideFromIndex(),

                    Text::make(__('Payment client ip'), 'payment_client_ip')->rules('max:15')->hideFromIndex(),

                    Number::make(__('Items quality'), 'items_quality')->rules('required', 'numeric', 'between:1,9999')
                        ->textAlign('right')->help('Valid quality value')->hideFromIndex(),

                    Currency::make(__('Price summary'), 'price_summary')->sortable()
                        ->rules('required', 'numeric', 'between:0,99999999')
                        ->textAlign('right')->help('Valid money value')
                        ->currency(\config('app.app_currency'))->showOnPreview(),*/


/*                    Date::make(__('Completed by manager at'), 'completed_by_manager_at')
                        ->showOnUpdating(function () use ($status) { // in edit mode "/nova/resources/orders/17/edit"
                            return $status === OrderStatusEnum::COMPLETED;
                        })
                        ->showOnDetail(function () use ($status) { // in view mode "/nova/resources/orders/17"
                            return $status === OrderStatusEnum::COMPLETED;
                        })
                        ->displayUsing(fn($value) => $value ? DateConv::getFormattedDate($value) : '')
                        ->sortable(),
                    Date::make(__('Expires at'), 'expires_at')
                        ->showOnUpdating(function () use ($status) { // in edit mode "/nova/resources/orders/17/edit"
                            return $status === OrderStatusEnum::INVOICE;
                        })
                        ->showOnDetail(function () use ($status) {  // in view mode "/nova/resources/orders/17"
                            return $status === OrderStatusEnum::INVOICE;
                        })
                        ->displayUsing(fn($value) => $value ? DateConv::getFormattedDate($value) : '')
                        ->sortable(),*/
                ]),
//                Tab::make('Billing Information', $this->orderHelper->getBillingFields($this, $isEdit, $status)),
                Tab::make('Billing Information', $this->getBillingFields())->showIf(condition:$otherShipping),
            ]),
        ];

    }


    private function getBillingFields(): array
    {
        return [
/*            Text::make(__('Billing first name'), 'billing_first_name')->rules('max:50')->hideFromIndex(),
            Text::make(__('Billing phone'), 'billing_phone')->rules('max:20')->hideFromIndex(),
            Text::make(__('Billing last name'), 'billing_last_name')->rules('max:50')->sortable()->hideFromIndex(),
            Text::make(__('Billing company'), 'billing_company')->rules('max:100')->sortable()->hideFromIndex(),
            Email::make(__('Billing email'), 'billing_email')->rules('max:100')->hideFromIndex(),

            Text::make(__('Billing country'), 'billing_country')->rules('max:2')->hideFromIndex(),
            // NSN_TODO
            Text::make(__('Billing address'), 'billing_address')->rules('max:100')->hideFromIndex(),
            Text::make(__('Billing address 2'), 'billing_address2')->rules('max:100')->sortable()->hideFromIndex(),

            Text::make(__('Billing city'), 'billing_city')->rules('max:50')->hideFromIndex(),
            Text::make(__('Billing state'), 'billing_state')->rules('max:100')->hideFromIndex(),
            Text::make(__('Billing first name'), 'billing_first_name')->rules('max:50')->hideFromIndex(),
            Text::make(__('Billing postcode'), 'billing_postcode')->rules('max:100')->hideFromIndex(),*/

        ];
    }
    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     *
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [
//            new MostActiveUsersWithProcessingOrders
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     *
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     *
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
