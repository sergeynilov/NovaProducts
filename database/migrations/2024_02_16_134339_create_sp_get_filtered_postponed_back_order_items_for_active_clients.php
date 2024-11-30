<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        return;
        $procedure = "
create procedure sp_getFilteredPostponedBackOrderItemsForActiveClients(
    IN in_creatorId bigint unsigned,
    IN in_status varchar(1),
    IN in_expiresAtFrom datetime,
    IN in_expiresAtTill datetime
)
BEGIN
-- get all postponed_back_order_items with status \"Processing - show related active client
-- call sp_getFilteredPostponedBackOrderItemsForActiveClients( /*in_creatorId*/ 12, /*in_Status*/ 'A', /*in_expiresAtFrom*/ '2024-01-01', /*in_expiresAtTill*/ '2024-12-01' );

-- SELECT PBOI.id, PBOI.creator_id, PBOI.order_id, PBOI.order_id, PBOI.product_id, PBOI.expires_at, PBOI.total_price,
-- users.status, users.name, users.email
--   FROM postponed_back_order_items as PBOI
--     LEFT JOIN users on users.id = PBOI.creator_id and users.status = 'A'  AND(12 = users.id OR ISNULL(12) ) -- active users
--     WHERE PBOI.status =  'P'  AND
--     PBOI.expires_at BETWEEN '2024-01-01' AND '2024-12-01'

SELECT PBOI.id, PBOI.creator_id, PBOI.order_id, PBOI.order_id, PBOI.product_id, PBOI.expires_at, PBOI.total_price,
users.status, users.name, users.email
  FROM postponed_back_order_items as PBOI
    LEFT JOIN users on users.id = PBOI.creator_id and users.status = 'A' AND(in_creatorId = users.id OR ISNULL(in_creatorId) ) -- active users
    WHERE PBOI.status =  in_status AND
    PBOI.expires_at BETWEEN  in_expiresAtFrom AND in_expiresAtTill AND in_expiresAtTill;

END
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
