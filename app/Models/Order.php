<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Enums\OrderOtherShippingEnum;
use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use KirschbaumDevelopment\NovaChartjs\Traits\HasChart;
use KirschbaumDevelopment\NovaChartjs\Contracts\Chartable;

class Order extends Model implements Chartable
{
    use HasChart;

    protected $table = 'orders';
    protected $primaryKey = 'id';
    public $timestamps = true;

//    use HasFactory;

    protected $casts
        = [
            'expires_at' => 'date',
            'last_operation_date' => 'date',
            'completed_by_manager_at' => 'datetime',
            'price_summary' => MoneyCast::class,
            'status' => OrderStatusEnum::class,
            'other_shipping' => OrderOtherShippingEnum::class,
            'updated_at' => 'datetime',
            'created_at' => 'datetime',

        ];

    protected $fillable
        = [
            'creator_id', 'postponed_back_order_item_id', 'billing_first_name', 'billing_last_name', 'billing_company',
            'billing_phone',
            'billing_email', 'billing_country', 'billing_address', 'billing_address2', 'billing_city', 'billing_state',
            'billing_postcode', 'info', 'price_summary', 'items_quality', 'payment', 'currency', 'status',
            'order_number', 'other_shipping', 'payment_client_ip', 'last_operation_date', 'mode', 'manager_id',
            'completed_by_manager', 'completed_by_manager_at', 'created_at'
        ];

    /*             $table->foreignId('creator_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreignId('postponed_back_order_item_id')->references('id')->on('postponed_back_order_items')->onUpdate('RESTRICT')->onDelete('RESTRICT')->nullable();
 */
//        [ 'D', 'I', 'C', 'P', 'O', 'R' ])->comment(    'D - Draft, I-Invoice, C-Cancelled, P-Processing, O - Completed, R - Refunded');

//    protected $appends = ['total_price'];

    /**
     * Should return settings for Nova Chart in prescribed format
     *
     * @return array
     */
    public static function getNovaChartjsSettings(): array
    {
        return [
            'default' => [
//                'type' => 'line',
                'type' => 'bar',
                'titleProp' => 'name',
                'identProp' => 'id',
                'height' => 400,
                'indexColor' => '#999999',
                'color' => '#FF0000',
                'parameters' => [
                    'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October',
                    'November', 'December'
                ],
                'options' => ['responsive' => true, 'maintainAspectRatio' => false],
            ]
        ];
    }


    /**
     * Return a list of additional datasets added to chart
     *
     * @return array
     */
//    public function getAdditionalDatasets(): array
//    {
//        return [
//            'default' => [
//                [
//                    'label' => 'Average Sales',
//                    'borderColor' => '#f87900',
//                    'data' => [80, 40, 62, 79, 80, 90, 79, 90, 90, 90, 92, 91],
//                ],
//            ]
//        ];
//    }

    public function getAdditionalDatasets(): array
    {
        return [
            'default' => [
                [
                    'label' => 'Minimum Required',
                    'borderColor' => '#f87900',
                    'fill' => '+1',
                    'backgroundColor' => 'rgba(20,20,20,0.2)',//For bar charts, this will be the fill color of the bar
                    'data' => [8, 7, 12, 19, 12, 10, 19, 9, 10, 20, 12, 11],
                ],
                [
                    'label' => 'Target',
                    'borderColor' => '#007979',
                    'fill' => false,
                    'data' => [80, 40, 62, 79, 80, 90, 79, 90, 90, 90, 92, 91],
                ],
            ]
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function scopeGetByCreatorId($query, $creatorId = null)
    {
        if (empty($creatorId)) {
            return $query;
        }

        return $query->where($this->table.'.creator_id', $creatorId);
    }

    public function postponedBackOrderItem()
    {
        return $this->belongsTo('App\Models\User', 'postponed_back_order_item_id', 'id');
    }

    public function scopeGetPostponedBackOrderItemId($query, $postponedBackOrderItemId = null)
    {
        if (empty($postponedBackOrderItemId)) {
            return $query;
        }

        return $query->where($this->table.'.postponedBackOrderItemId', $postponedBackOrderItemId);
    }

    public function scopeOnlyProcessing($query)
    {
        return $query->where($this->table.'.status', OrderStatusEnum::PROCESSING);
    }

    public function scopeOnlyCompleted($query)
    {
        return $query->where($this->table.'.status', OrderStatusEnum::COMPLETED);
    }

    public function scopeOnlyInvoice($query)
    {
        return $query->where($this->table.'.status', OrderStatusEnum::INVOICE)->orderBy('expires_at', 'desc');
    }

    public function scopeGetByStatus($query, OrderStatusEnum $status = null)
    {
        if ( ! empty($status)) {
            if (is_array($status)) {
                $query->whereIn($this->table.'.status', $status->value);
            } else {
                $query->where($this->table.'.status', $status->value);
            }
        }

        return $query;
    }

    /*             $table->foreignId('creator_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->string('billing_first_name', 50);
            $table->string('billing_last_name', 50);
            $table->string('billing_company', 100);
            $table->string('billing_phone', 20);
            $table->string('billing_email', 100);
            $table->string('billing_country', 2);
            $table->string('billing_address', 100);
            $table->string('billing_address2', 100);
            $table->string('billing_city', 50);
            $table->string('billing_state', 100);
            $table->string('billing_postcode', 6);

            $table->mediumText('info')->nullable();
            $table->smallInteger('items_summary')->unsigned()->comment('Money sum / 100');
            $table->smallInteger('items_quality')->unsigned();
            $table->string('payment', 2)->nullable();
            $table->string('currency', 2)->nullable();
            $table->enum('status', [ 'D', 'I', 'C', 'P', 'O', 'R' ])->comment(    'D - Draft, I-Invoice, C-Cancelled, P-Processing, O - Completed, R - Refunded');

            $table->string('order_number', 15);
            $table->bool('other_shipping');
            $table->string('payment_client_ip', 15)->nullable();
            $table->date('last_operation_date')->nullable();
            $table->enum('mode', [ 'T', 'L' ])->comment(    'T - , L -');

            $table->foreignId('manager_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->bool('completed_by_manager')->default(false);
            $table->date('completed_by_manager_at')->nullable();
 */

    /*
    private static $orderAcceptedValueArray = array('1' => 'Accepted', '0' => 'New');

    protected $casts
        = [
        ];

    public function orderCategories(): HasMany
    {
        return $this->hasMany(OrderCategory::class, 'order_id', 'id');
    }

    public function orderCities(): HasMany
    {
        return $this->hasMany(OrderCity::class, 'order_id', 'id');
    }



    public function categories(): HasManyThrough
    {
        return $this->hasManyThrough(OrderCategory::class, Category::class);
    }

    public function scopeOnlyActiveCategories() : HasManyThrough
    {
        return $this->hasManyThrough(OrderCategory::class, Category::class)
            ->where($this->table . '.active', true);
    }


    public function scopeGetByPublishedAt($query, $filter_published_at= null, string $sign= null)
    {
        if (!empty($filter_published_at)) {
            if (!empty($sign)) {
                if (in_array($sign, ['=', '<', '>', '<=', '>=', '!-', '<>'])) {
                    $query->whereRaw( DB::getTablePrefix().with(new Order)->getTable() . '.published_at ' . $sign . ' ?', [$filter_published_at]);
                }
            } else {
                $query->where($this->table . '.published_at', $filter_published_at);
            }
        }
        return $query;
    }


    public function scopeGetBySalePrice($query, $filter_sale_price= null, string $sign= null)
    {
        if (!empty($filter_sale_price)) {
            if (!empty($sign)) {
                if (in_array($sign, ['=', '<', '>', '<=', '>=', '!-', '<>'])) {
                    $query->whereRaw( DB::getTablePrefix().with(new Order)->getTable() . '.sale_price ' . $sign . ' ?', [$filter_sale_price]);
                }
            } else {
                $query->where($this->table . '.sale_price', $filter_sale_price);
            }
        }
        return $query;
    }


    public function scopeGetByStockQty($query, $filter_stock_qty= null, string $sign= null)  //   stock_qty
    {
        if (!empty($filter_stock_qty)) {
            if (!empty($sign)) {
                if (in_array($sign, ['=', '<', '>', '<=', '>=', '!-', '<>'])) {
                    $query->whereRaw( DB::getTablePrefix().with(new Order)->getTable() . '.stock_qty ' . $sign . ' ?', [$filter_stock_qty]);
                }
            } else {
                $query->where($this->table . '.stock_qty', $filter_stock_qty);
            }
        }
        return $query;
    }


    public function scopeGetByCategoryId($query, $category_id = null)
    {
        if (empty($category_id)) {
            return $query;
        }

        return $query->where($this->table . '.category_id', $category_id);
    }

    public function scopeGetByCityId($query, $city_id = null)
    {
        if (empty($city_id)) {
            return $query;
        }

        return $query->where($this->table . '.city_id', $city_id);
    }

    public function scopeGetByTitle($query, $title = null)
    {
        if (empty($title)) {
            return $query;
        }
        return $query->where($this->table . '.title', 'like', '%' . $title . '%');
    }

    public function scopeGetByIsStock($query, $in_stock = null)
    {
        if ( ! isset($in_stock) or strlen($in_stock) == 0) {
            return $query;
        }

        return $query->where('in_stock', $in_stock);
    }


    public function scopeGetByHasDiscountPrice($query, $discount_price_allowed = null)
    {
        if ( ! isset($discount_price_allowed) or strlen($discount_price_allowed) == 0) {
            return $query;
        }

        return $query->where('discount_price_allowed', $discount_price_allowed);
    }

    public function scopeGetByIsFeatured($query, $is_featured = null)
    {
        if ( ! isset($is_featured) or strlen($is_featured) == 0) {
            return $query;
        }

        return $query->where('is_featured', $is_featured);
    }

    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class, 'discount_order');
    }

    */
    /*    public static function getOrderAcceptedValueArray($key_return = true): array
        {
            $resArray = [];
            foreach (self::$orderAcceptedValueArray as $key => $value) {
                if ($key_return) {
                    $resArray[] = ['key' => $key, 'label' => $value];
                } else {
                    $resArray[$key] = $value;
                }
            }

            return $resArray;
        }


        public static function getOrderAcceptedLabel(string $accepted): string
        {
            if ( ! empty(self::$orderAcceptedValueArray[$accepted])) {
                return self::$orderAcceptedValueArray[$accepted];
            }

            return self::$orderAcceptedValueArray[0];
        }




        public static function getOrderValidationRulesArray(array $skipFieldsArray= []): array
        {
            $validationRulesArray = [
                'title'           => 'required|min:5',
                'content_message' => 'required|min:20',
                'author_id'       => 'required|integer|exists:' . $this->table . ',id',
                'accepted'        => 'required|in:' . getValueLabelKeys(Order::getOrderAcceptedValueArray(false)),
            ];
            foreach( $skipFieldsArray as $next_field ) {
                if(!empty($validationRulesArray[$next_field])) {
                    unset($validationRulesArray[$next_field]);
                }
            }

            return $validationRulesArray;
        }*/


}
