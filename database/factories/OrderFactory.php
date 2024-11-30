<?php

namespace Database\Factories;

use App\Enums\NovaSettingsParamEnum;
use App\Library\Facades\AppSettingsFacade;
use App\Models\OrderItem;
use App\Models\OrderOperation;
use App\Models\OrderShipping;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{

    /*
php artisan db:seed   ordersWithInitData
    */

    public function definition()
    {
//        $status = 'D'; // Draft,;
//        $status = 'C'; // Cancelled;
//        $status = 'O'; // Completed;
//        $status = 'I'; // Invoice;
//        $status = 'R'; // Refunded;
//        $status = 'P'; // Processing;
        $status = $this->faker->randomElement(['D', 'I', 'C', 'P', 'O', 'R']);
        $creator = $this->faker->randomElement(User::all());
        \Log::info(varDump($status, ' -1 $status::'));
        $countries = config('app.countries');

        $billingCountry = $this->faker->randomElement(array_keys($countries));
        $billingFirstName = $creator->name;
        $billingLastName = '';
        $creatorNames = Str::of($creator->name)->explode(' ');
//        \Log::info(varDump($creatorNames, ' -1 $creatorNames::'));
        if (count($creatorNames) === 2) {
            $billingFirstName = $creatorNames[0];
            $billingLastName = $creatorNames[1];
        }
        if (count($creatorNames) === 3 or count($creatorNames) === 4) {
            $billingFirstName = $creatorNames[1];
            $billingLastName = $creatorNames[2];
        }
        $orderNumber = 'Order # ' . Str::substr((string)Str::uuid(), 1, 4);
        $useShipping = $this->faker->randomElement([1, 2, 3, 4]) === 1;
        $paymentClientIp = null;
        $lastOperationDate = null;
        $completedByManagerAt = null;
        $expiresAt = null;
        $mode = 'l';
        $managerId = $creator->id;
        $createdAt = $this->faker
            ->dateTimeBetween('-3 months', '-1 day');
        if( $status === 'O') { // Completed;
            $completedByManagerAt = Carbon::parse($createdAt)->addHours($this->faker->numberBetween(0, 23));
            $lastOperationDate = $completedByManagerAt;
        }
        if( $status === 'I') { // I-Invoice

            if(rand(1, 2) === 1) {
                $expiresAt = Carbon::parse($createdAt)->addDays(AppSettingsFacade::getValue(NovaSettingsParamEnum::INVOICE_DAYS_BEFORE_EXPIRE));
            }
//            $lastOperationDate = $completedByManagerAt;
        }
        return [
            'creator_id' => $creator->id,
            'billing_first_name' => $billingFirstName,
            'billing_last_name' => $billingLastName,
            'billing_company' => $this->faker->title . ' billing company',
            'billing_phone' => $this->faker->phoneNumber,
            'billing_email' => $creator->email,
            'billing_country' => $billingCountry,
            'billing_address' => $this->faker->address,
            'info' => $this->faker->name() . ' info text',
            'price_summary' => 0,
            'items_quality' => 0,
            'payment' => 'PP',
            'currency' => 'USD',
            'status' => $status,
            'order_number' => $orderNumber,
            'other_shipping' => $useShipping,
            'payment_client_ip' => $paymentClientIp,
            'last_operation_date' => $lastOperationDate,
            'mode' => $mode,
            'manager_id' => $managerId,
            'expires_at' => $expiresAt,
            'completed_by_manager_at' => $completedByManagerAt,
            'created_at' => $createdAt,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) { // Order model is returned here
            $orderRelatedProducts = Product::limit($this->faker->biasedNumberBetween(1, 10))->get();
            $qtySummary = 0;
            $priceSummary = 0;
            foreach ($orderRelatedProducts as $product) { // All Products would be assigned to the Order
                $qty = $this->faker->biasedNumberBetween(1, 20);
                $orderItem = OrderItem::create([
                    'product_id' => $product->id,
                    'order_id' => $order->id,
                    'qty' => $qty,
                    'price' => $product->getAttribute('sale_price'),
                    'created_at' => $this->faker->dateTimeBetween('-1 month', '-1 hour'),
                ]);
                $qtySummary += $qty;
                $priceSummary += $product->getAttribute('sale_price');
                $order->setAttribute('items_quality', $qtySummary);
                $order->setAttribute('price_summary', $priceSummary);
                $order->save();

            } // foreach ($orderRelatedProducts as $product) { // All Products would be assigned to the Order

            $request = request();
            /*             $table->enum('status', [ 'D', 'I', 'C', 'P', 'O', 'R' ])->comment(    'D - Draft, I-Invoice, C-Cancelled, P-Processing, O - Completed, R - Refunded');   */

            /* enum OrderStatusEnum: string
{
    // These values are the same as enum values in db
    case DRAFT = 'D';
    case INVOICE = 'I';
    case CANCELLED = 'C';
    case PROCESSING = 'P';
    case COMPLETED = 'O';
    case REFUNDED = 'R';
 */

            $orderOperationsTemplates = [
                'D' => ['D' => 'ORDER_MADE'],
                'I' => ['I' => 'ORDER_MADE'],
                'C' => ['I' => 'ORDER_MADE', 'P' => 'PROCESSING', 'C' => 'CANCELLED'],
                'P' => ['I' => 'ORDER_MADE', 'P' => 'PROCESSING'],
                'O' => ['I' => 'ORDER_MADE', 'P' => 'PROCESSING', 'O' => 'PAYMENT_CHARGED'],
                'R' => ['I' => 'ORDER_MADE', 'P' => 'PROCESSING', 'O' => 'PAYMENT_CHARGED', 'R' => 'PAYMENT_REFUNDED']
            ];
/*            echo '$order->status::'.print_r($order->status,true);
            if(empty($order->status)) {
                return;
            }
            if(!isset($orderOperationsTemplates[$order->status->value])) {
                return;
            }*/
            $orderOperations = $orderOperationsTemplates[$order->status->value];
//                    dd($orderOperations);
//            \Log::info(varDump($orderOperations, ' -1 $orderOperations::'));
            $beforeStatus = null;
            foreach ($orderOperations as $nextStatus => $orderOperation) {
                OrderOperation::create([
                    'creator_id' => $order->creator_id,
                    'order_id' => $order->id,
                    'operation_type' => $orderOperation,
                    'before_status' => $beforeStatus,
                    'status' => $nextStatus,
                    'ip_address' => $request->ip(),
                ]);
                $beforeStatus = $nextStatus;
            }
            // foreach($orderOperations as $nextStatus => $orderOperation ) {

            if ($order->getAttribute('other_shipping')) {
                $countries = config('app.countries');
                OrderShipping::create([
                    'order_id' => $order->id,

                    'shipping_first_name' => 'Other ' . $order->getAttribute('billing_first_name'),
                    'shipping_last_name' => 'Other ' . $order->getAttribute('billing_last_name'),
                    'shipping_company' => 'Other ' . $order->getAttribute('billing_company'),
                    'shipping_phone' => Str::substr('Other ' . $order->getAttribute('billing_phone'), 1, 20),
                    'shipping_email' => 'Other ' . $order->getAttribute('billing_email'),
                    'shipping_country' => $this->faker->randomElement(array_keys($countries)),
                    'shipping_address' => 'Other ' . $order->getAttribute('billing_address'),
                    'shipping_address2' => 'Other ' . $order->getAttribute('billing_address') . ' 2',
                    'shipping_city' => 'Other shipping_city ',
                    'shipping_state' => 'Other shipping_state ',
                    'shipping_postcode' => 'Other  ',
                    'created_at' => $this->faker->dateTimeBetween('-1 month', '-1 hour'),
                ]);
            }
            /*
    /*1	1	1	ORDER_MADE	\N	D			127.0.0.1	2017-04-02 17:10:56.007092
2	1	1	PAYMENT_CHARGED	D	I			127.0.0.1	2017-04-02 17:11:01.818597
3	2	1	ORDER_MADE	\N	D			127.0.0.1	2017-04-02 17:11:07.731934
4	1	1	PROCESSING	I	P		\N	127.0.0.1	2017-04-02 17:13:09.004555
5	1	1	COMPLETED	P	O		\N	127.0.0.1	2017-04-02 17:13:17.504247
6	2	1	PAYMENT_CHARGED	D	I			127.0.0.1	2017-04-02 17:13:29.893368
7	2	1	CANCELLED*/

        }
        );
    }
}
