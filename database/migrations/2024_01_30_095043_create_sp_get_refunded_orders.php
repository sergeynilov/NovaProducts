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
        /* -- sp_getRefundedOrders
        SELECT orders.order_number, orders.status, orders.price_summary, orders.items_quality, products.id as products_id, products.title as products_title
            FROM orders LEFT JOIN
            order_items on order_items.order_id = orders.id RIGHT JOIN
            products on products.id = order_items.product_id AND ( products.id = 1 OR ISNULL(1) ) RIGHT JOIN
            order_operations on order_operations.order_id = orders.id AND
            order_operations.operation_type = 'PAYMENT_REFUNDED'
            WHERE ( orders.creator_id = 1 OR ISNULL(1) ) AND
            orders.created_at BETWEEN '2024-01-01' AND '2024-11-30'
         */
    $procedure = "
create procedure sp_getRefundedOrders(
    IN in_productId bigint unsigned,
    IN in_creatorId bigint unsigned,
    IN in_operationType varchar(20),
    IN in_createdAtFrom timestamp,
    IN in_createdAtTill timestamp
)
BEGIN
  -- call sp_getRefundedOrders(@in_productId := 1, @in_creatorId := 1, @in_operationType := 'PAYMENT_REFUNDED', @in_createdAtFrom := '2024-01-01', @in_createdAtTill := '2024-11-30' );

-- SELECT orders.id, orders.order_number, orders.status, orders.price_summary, orders.items_quality, orders.last_operation_date,
-- products.id as products_id, products.title as products_title
--     FROM orders INNER JOIN
--     order_items on order_items.order_id = orders.id RIGHT JOIN
--     products on products.id = order_items.product_id AND ( products.id = 1 OR ISNULL(1) ) INNER JOIN
--     order_operations on order_operations.order_id = orders.id AND
--     order_operations.operation_type = 'PROCESSING'
--     WHERE ( orders.creator_id = 10 OR ISNULL(1) ) AND
--     orders.created_at BETWEEN '2023-01-01' AND '2024-11-30'

SELECT orders.id, orders.order_number, orders.status, orders.price_summary, orders.items_quality, orders.last_operation_date,
   products.id as products_id, products.title as products_title
    FROM orders INNER JOIN
    order_items on order_items.order_id = orders.id RIGHT JOIN
    products on products.id = order_items.product_id AND ( products.id = in_productId OR ISNULL(in_productId) ) INNER JOIN
    order_operations on order_operations.order_id = orders.id AND
    ( order_operations.operation_type = in_operationType OR ISNULL(in_operationType) )
    WHERE ( orders.creator_id = in_creatorId OR ISNULL(in_creatorId) ) AND
    ( orders.created_at BETWEEN in_createdAtFrom AND in_createdAtTill  );

END;

";
        DB::unprepared("DROP procedure IF EXISTS sp_getRefundedOrders");
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        return;
        DB::unprepared("DROP procedure IF EXISTS sp_getRefundedOrders");
    }
};
