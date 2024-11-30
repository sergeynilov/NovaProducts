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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->references('id')->on('orders')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('product_id')->references('id')->on('products')->onUpdate('RESTRICT')->onDelete('RESTRICT');

            $table->integer('qty')->unsigned();
            $table->integer('price')->unsigned()->comment('Cast on client must be used - Money sum = value/100');
            $table->decimal('total_price', 8, 2)
                ->storedAs('price * qty') // Define the virtual column
                ->index()->comment('Cast on client must be used - Money sum = value/100'); // Index the virtual column
            $table->timestamps();

            $table->index(['order_id', 'product_id', 'qty'], 'order_items_3fields_index');
        });
        /* public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->decimal('unit_price', 8, 2);
        $table->integer('quantity');
        $table->decimal('total_price', 8, 2)
            ->storedAs('unit_price * quantity') // Define the virtual column
            ->index(); // Index the virtual column
        $table->timestamps();
    }); */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item');
    }
};
