<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderOperation;
use App\Models\OrderShipping;
use App\Models\PostponedBackOrderItem;
use App\Models\Product;
use App\Models\Salesman;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class PostponedBackOrderItemFactory extends Factory
{
    /*
php artisan db:seed   ordersWithInitData
    */

    public function definition()
    {
        $client = $this->faker->randomElement(Client::all());
        $salesman = $this->faker->randomElement(Salesman::all());
        $order = $this->faker->randomElement(Order::all());
        $product = $this->faker->randomElement(Product::all());

        $status = $this->faker->randomElement([ /* 'I', 'C',  */ 'P', 'O'/*, 'R'*/]);
//        $table->enum('status', [ 'I', 'C', 'P', 'O', 'R' ])->comment(    'I-Invoice, C-Cancelled, P-Processing, O - Completed, R - Refunded');
        /*  */
        $expiresAt = $this->faker->dateTimeBetween('+1 day', '+3 months');
        $createdAt = $this->faker->dateTimeBetween('-3 months', '-1 day');
        $qty = $this->faker->biasedNumberBetween(1, 20);

        /*     public function up(): void
    {
        Schema::create('postponed_back_order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('creator_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreignId('order_id')->references('id')->on('orders')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('product_id')->references('id')->on('products')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->enum('status', [ 'I', 'C', 'P', 'O', 'R' ])->comment(    'I-Invoice, C-Cancelled, P-Processing, O - Completed, R - Refunded');

            $table->datetime('expires_at');

            $table->integer('qty')->unsigned();
            $table->integer('price')->unsigned()->comment('Cast on client must be used - Money sum = value/100');
            $table->decimal('total_price', 8, 2)
                ->storedAs('price * qty') // Define the virtual column
                ->index()->comment('Cast on client must be used - Money sum = value/100'); // Index the virtual column

            $table->foreignId('manager_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');

            $table->timestamps();

            $table->index(['creator_id', 'status', 'product_id', 'qty'], 'postponed_back_order_items_4fields_index');
            $table->index(['product_id', 'status', 'manager_id', 'qty', 'expires_at'], 'postponed_back_order_items_5fields_index');
            $table->index([ 'status', 'creator_id', 'qty', 'expires_at'], 'postponed_back_order_items_42fields_index');
//            $table->index(['creator_id', 'status', 'payment', 'currency', 'order_number'], 'orders_5fields_index');
        });
        Artisan::call('db:seed', array('--class' => 'postponedBackOrderItemsWithInitData'));

    }
 */

        return [
            'creator_id' => $client->id,
            'order_id' => $order->id,
            'product_id' => $product->id,
            'status' => $status,
//            'order_id' => $order->id,
            'qty' => $qty,
            'price' => $product->getAttribute('sale_price'),
            'manager_id' => $salesman->id,
            'expires_at' => $expiresAt,
            'created_at' => $createdAt,
        ];
    }


    public function configure()
    {
        return $this->afterCreating(function (PostponedBackOrderItem $postponedBackOrderItem) { // PostponedBackOrderItem model is returned here
            \Log::info(varDump($postponedBackOrderItem, ' -1 $postponedBackOrderItem::'));
            return;
            if($postponedBackOrderItem->status === 'O' ) { // Completed $postponedBackOrderItem - must create Order and OrderItem rows

//            $orderRelatedProducts = Product::limit($this->faker->biasedNumberBetween(1, 10))->get();
            $order = Order::create([
                'creator_id' => $postponedBackOrderItem->getAttribute('creator_id'),
                'product_id' => $postponedBackOrderItem->getAttribute('product_id'),
                'order_id' => $order->id,
                'qty' => $postponedBackOrderItem->getAttribute('qty'),
                'price' => $postponedBackOrderItem->getAttribute('price')
/*         =  'postponed_back_order_item_id', 'billing_first_name', 'billing_last_name', 'billing_company', 'billing_phone',
            'billing_email', 'billing_country', 'billing_address', 'billing_address2', 'billing_city', 'billing_state',
            'billing_postcode', 'info', 'price_summary', 'items_quality', 'payment', 'currency', 'status', 'order_number', 'other_shipping', 'payment_client_ip', 'last_operation_date', 'mode', 'manager_id', 'completed_by_manager', 'completed_by_manager_at', 'created_at'];
 */
            ]);
            $qtySummary = 0;
            $priceSummary = 0;
                $orderItem = OrderItem::create([
                    'product_id' => $postponedBackOrderItem->id,
                    'order_id' => $order->id,
                    'qty' => $postponedBackOrderItem->getAttribute('qty'),
                    'price' => $postponedBackOrderItem->getAttribute('price')
                ]);
                $qtySummary += $qty;
                $priceSummary += $product->getAttribute('sale_price');
                $order->setAttribute('items_quality', $qtySummary);
                $order->setAttribute('price_summary', $priceSummary);
                $order->save();


            }
            // Completed $postponedBackOrderItem - must create Order and OrderItem rows

            return;
            $request = request();
            /*             $table->enum('status', [ 'D', 'I', 'C', 'P', 'O', 'R' ])->comment(    'D - Draft, I-Invoice, C-Cancelled, P-Processing, O - Completed, R - Refunded');   */

            $orderOperationsTemplates = [
                'D' => ['D' => 'ORDER_MADE'],
                'I' => ['I' => 'ORDER_MADE'],
                'C' => ['I' => 'ORDER_MADE', 'P' => 'PROCESSING', 'C' => 'CANCELLED'],
                'P' => ['I' => 'ORDER_MADE', 'P' => 'PROCESSING'],
                'O' => ['I' => 'ORDER_MADE', 'P' => 'PROCESSING', 'O' => 'PAYMENT_CHARGED'],
                'R' => ['I' => 'ORDER_MADE', 'P' => 'PROCESSING', 'O' => 'PAYMENT_CHARGED', 'R' => 'PAYMENT_REFUNDED']
            ];
            $orderOperations = $orderOperationsTemplates[$order->status];
            \Log::info(varDump($orderOperations, ' -1 $orderOperations::'));
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
                    'shipping_postcode' => 'Other  '
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
