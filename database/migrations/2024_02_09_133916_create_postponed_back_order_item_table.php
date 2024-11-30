<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postponed_back_order_items');
    }
};
