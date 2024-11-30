<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
/*    public function up(): void
    {
        Schema::create('sp_getProductDiscountsByRegionName', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }*/

    public function up(): void
    {
        return;
        $procedure = "
create procedure sp_getProductDiscountsByRegionName(
    IN in_citiesRegion varchar(50),
    IN in_discountsMinQty int unsigned,
    IN in_discountsMaxQty int unsigned
)
BEGIN
  -- call sp_getProductDiscountsByRegionName(@in_citiesRegion := 'Aberdeen City', @in_discountsMinQty := 100, @in_discountsMaxQty := 400 );

call sp_getProductDiscountsByRegionName(@in_citiesRegion := 'Washington, England', @in_discountsMinQty := 150, @in_discountsMaxQty := 400 );
-- SELECT products.id, products.title, products.sale_price,
--     cities.address AS citiy_address, cities.id AS citiy_id, discounts.min_qty, discounts.max_qty,
--     discounts.id as discounts_id, discounts.name as discounts_name
--     FROM products
--     INNER JOIN
--         products_cities on products_cities.product_id = products.id
--     INNER JOIN
--         cities on cities.id = products_cities.city_id
--     LEFT JOIN
--         discount_product on discount_product.product_id = products.id
--     LEFT JOIN
--         discounts on discounts.id = discount_product.discount_id
--     WHERE cities.region = 'Washington, England' AND
--        ( discounts.min_qty > 200 AND discounts.max_qty <= 300 ) AND
-- 100  1000
--        discounts.active = true

SELECT products.id, products.title, products.sale_price,
    cities.address AS citiy_address, cities.id AS citiy_id, discounts.min_qty, discounts.max_qty,
    discounts.id as discounts_id, discounts.name as discounts_name
    FROM products
    INNER JOIN
        products_cities on products_cities.product_id = products.id
    INNER JOIN
        cities on cities.id = products_cities.city_id
    LEFT JOIN
        discount_product on discount_product.product_id = products.id
    LEFT JOIN
        discounts on discounts.id = discount_product.discount_id
    WHERE cities.region = in_citiesRegion AND
       ( discounts.min_qty > in_discountsMinQty AND discounts.max_qty <= in_discountsMaxQty ) AND
       discounts.active = true;

END;

";

        /* -- sp_getProductDiscountsByRegionName
        SELECT orders.order_number, orders.status, orders.price_summary, orders.items_quality, products.id as products_id, products.title as products_title
            FROM orders LEFT JOIN
            order_items on order_items.order_id = orders.id RIGHT JOIN
            products on products.id = order_items.product_id AND ( products.id = 1 OR ISNULL(1) ) RIGHT JOIN
            order_operations on order_operations.order_id = orders.id AND
            order_operations.operation_type = 'PAYMENT_REFUNDED'
            WHERE ( orders.creator_id = 1 OR ISNULL(1) ) AND
            orders.created_at BETWEEN '2024-01-01' AND '2024-11-30'
         */
        DB::unprepared("DROP procedure IF EXISTS sp_getProductDiscountsByRegionName");
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        return;
        DB::unprepared("DROP procedure IF EXISTS sp_getProductDiscountsByRegionName");
    }
};
