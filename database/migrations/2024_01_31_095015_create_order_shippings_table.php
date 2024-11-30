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
        Schema::create('order_shippings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->references('id')->on('orders')->onUpdate('CASCADE')->onDelete('CASCADE');

            $table->string('shipping_first_name', 50)->nullable();
            $table->string('shipping_last_name', 50)->nullable();
            $table->string('shipping_company', 100)->nullable();
            $table->string('shipping_phone', 20)->nullable();
            $table->string('shipping_email', 100)->nullable();
            $table->char('shipping_country', 2)->nullable();
            $table->string('shipping_address', 100)->nullable();
            $table->string('shipping_address2', 100)->nullable();
            $table->string('shipping_city', 50)->nullable();
            $table->string('shipping_state', 100)->nullable();
            $table->char('shipping_postcode', 6)->nullable();

            $table->index([ 'shipping_country', 'shipping_state', 'shipping_city', 'shipping_postcode'], 'order_shippings_4fields_index');
            $table->timestamp('created_at')->useCurrent();

        });
        \Artisan::call('db:seed', array('--class' => 'ordersWithInitData'));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_shippings');
    }
};
