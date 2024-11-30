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
        Schema::create('discounts', function (Blueprint $table) {
            $table->tinyIncrements('id')->unsigned();
            $table->string('name',100)->unique();
            $table->boolean('active')->default(false);
            $table->date('active_from')->nullable();
            $table->date('active_till')->nullable();
            $table->tinyInteger('sort_order')->unsigned();

            $table->integer('min_qty')->nullable()->unsigned();
            $table->integer('max_qty')->nullable()->unsigned();
            $table->decimal('percent', 7, 2)->unsigned();

            $table->mediumText('description');
            $table->timestamps();

            $table->index(['sort_order', 'active'], 'discounts_sort_order_active_index');
            $table->index(['active', 'name'], 'discounts_active_name_index');
            $table->index(['active', 'min_qty', 'max_qty'], 'discounts_active_min_qty_max_qty_index');
        });

        \Artisan::call('db:seed', array('--class' => 'discountsWithInitData'));

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
