<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {

            $table->index(['category_id', 'status', 'regular_price'],'products_category_id_status_regular_price_at_index');
            $table->index(['status', 'published_at'],'products_status_published_at_index');

            $table->index(['status', 'in_stock', 'discount_price_allowed', 'is_featured', 'sale_price', 'published_at', 'stock_qty'], 'products_status_5fields_index');

            $table->unique(['slug'], 'products_slug_index');
            $table->unique(['sku'], 'products_sku_index');
            $table->index(['user_id', 'status', 'sale_price', 'published_at'], 'products_user_id_3fields_index');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['status_title_published_at']);
            $table->dropIndex(['status_5fields']);
            $table->dropIndex(['slug']);
            $table->dropIndex(['sku']);
            $table->dropIndex(['user_id_3fields']);
        });
    }
};
