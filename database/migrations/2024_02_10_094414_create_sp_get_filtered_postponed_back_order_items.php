<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        return;
            $procedure = "
create procedure sp_getFilteredPostponedBackOrderItems(
    IN in_creatorId bigint unsigned,
    IN in_userStatus varchar(1),
    IN in_productStatus varchar(1),
    IN in_qtyLessThen int unsigned
)
BEGIN
-- get all postponed_back_order_items with status \"Processing - show related active client
-- call sp_getFilteredPostponedBackOrderItems( /*in_creatorId*/ 12, /*in_userStatus*/ 'A', /*in_productStatus*/ 'A', /*in_qtyLessThen*/ 5 );

-- SELECT pboi.id, pboi.status, pboi.expires_at, users.id, users.name, users.status, p.id, p.title, p.status
--     FROM postponed_back_order_items pboi
--     LEFT JOIN
--         users ON users.id = pboi.creator_id   AND users.status = 'A'
--     LEFT JOIN
--         products AS p ON p.id = pboi.product_id  AND p.status = 'A'
--     WHERE pboi.status = 'P' AND
--         ( pboi.creator_id = 12 OR 12 = NULL) AND
--         pboi.qty >5
--     ORDER BY pboi.expires_at desc


SELECT pboi.id, pboi.status, pboi.expires_at, users.id, users.name, users.status, p.id, p.title, p.status
    FROM postponed_back_order_items pboi
    LEFT JOIN
        users ON users.id = pboi.creator_id   AND users.status = in_userStatus
    LEFT JOIN
        products AS p ON p.id = pboi.product_id  AND p.status = in_productStatus
    WHERE pboi.status = 'P' AND
        ( pboi.creator_id = in_creatorId OR in_creatorId = NULL) AND
        pboi.qty > in_qtyLessThen
    ORDER BY pboi.expires_at desc;

END;

";
        DB::unprepared("DROP procedure IF EXISTS sp_getFilteredPostponedBackOrderItems");
        DB::unprepared($procedure);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        return;
        DB::unprepared("DROP procedure IF EXISTS sp_getFilteredPostponedBackOrderItems");
    }
};
