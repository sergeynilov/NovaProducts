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
            Schema::create('discount_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->references('id')->on('products')->onDelete('CASCADE');

            $table->unsignedTinyInteger('discount_id');
            $table->foreign('discount_id')->references('id')->on('discounts')->onUpdate('RESTRICT')->onDelete('CASCADE');

            $table->timestamp('created_at')->useCurrent();

            $table->unique(['product_id', 'discount_id'], 'discount_product_product_id_discount_id_index');

        });
        \Artisan::call('db:seed', array('--class' => 'discountProductsWithInitData'));

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_product');
    }
};
