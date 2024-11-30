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
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->foreignId('product_id')->references('id')->on('products')->onDelete('CASCADE');

            $table->string('key', 255);
            $table->string('value', 255);
            $table->timestamps();

            $table->index(['product_id', 'key'], 'product_attributes__product_id_key_index');
        });

        \Artisan::call('db:seed', array('--class' => 'productAttributesWithInitData'));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attribute');
    }
};
