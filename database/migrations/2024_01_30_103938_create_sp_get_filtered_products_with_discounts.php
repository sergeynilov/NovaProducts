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
        return;
        $procedure = "

CREATE PROCEDURE sp_getFilteredProductsWithDiscounts( IN in_status  varchar(1), IN in_discountPriceAllowed tinyint unsigned, IN in_in_stock  varchar(1), IN in_stock_qty mediumint, IN in_discounts_qty mediumint )
BEGIN

DECLARE branch_id smallint unsigned;
DECLARE branch_name varchar(100);
DECLARE done INT DEFAULT FALSE;

SELECT products.id, products.title, products.sale_price,
    GROUP_CONCAT(CONCAT(discounts.name, ': ', discounts.min_qty, ': ', discounts.max_qty, ': ', discounts.percent)) AS discount_info
FROM products
    LEFT JOIN discount_product ON discount_product.product_id = products.id
    LEFT JOIN discounts on discounts.id = discount_product.discount_id
    WHERE ( products.status = in_status OR ISNULL(in_status) ) AND
      ( products.discount_price_allowed = in_discountPriceAllowed OR ISNULL(in_discountPriceAllowed)) AND
      ( products.in_stock = 1 OR ISNULL(in_in_stock) ) AND
      ( products.stock_qty >= in_stock_qty OR ISNULL(in_stock_qty) ) AND
      ( in_discounts_qty BETWEEN discounts.min_qty  AND discounts.max_qty OR ISNULL(in_discounts_qty))
    -- from 200 till 300
    GROUP BY products.id, products.title, products.sale_price;

END;

";

        /*
         */
        DB::unprepared("DROP procedure IF EXISTS sp_getFilteredProductsWithDiscounts");
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        return;
        DB::unprepared("DROP procedure IF EXISTS sp_getFilteredProductsWithDiscounts");
    }
};
