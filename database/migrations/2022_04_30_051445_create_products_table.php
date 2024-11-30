<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->string('title', 255);

            $table->enum('status',
                ['D', 'P', 'A', 'I'])->default('D')->comment(' D => Draft, P=>Pending Review, A=>Active, I=>Inactive');
//            CALL sp_getFilteredProductsWithDiscounts(@in_status := 'A', @in_discountPriceAllowed := 1, @in_in_stock := 1, @in_stock_qty := 2, @in_discounts_qty := 3 );

            $table->string('slug', 260)->unique();
            $table->string('sku', 100)->unique();

            $table->unsignedSmallInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('RESTRICT')->onDelete('CASCADE');

            $table->unsignedSmallInteger('brand_id');
            $table->foreign('brand_id')->references('id')->on('brands')->onUpdate('RESTRICT')->onDelete('CASCADE');

            $table->unsignedInteger('regular_price')->nullable();  // The “Regular Price” is the normal price for your product.
            $table->unsignedInteger('sale_price')->nullable(); // The “Sale Price” is a price for if you are discounting from your “Regular Price”.
            $table->boolean('in_stock')->default(false);
            $table->unsignedMediumInteger('stock_qty')->default(0);
            $table->boolean('discount_price_allowed')->default(false);
            $table->boolean('is_featured')->default(false);


            $table->mediumText('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->dateTime('published_at')->nullable();

            $table->string('pdf_help_file', 100)->nullable();
            $table->string('audio_help_file', 100)->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
