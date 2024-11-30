<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $procedure = "
create function f_formatMoneySum(moneyValue int unsigned) returns varchar(100) deterministic
BEGIN
    DECLARE currencyLabel VARCHAR(10);
    DECLARE moneyDecimal VARCHAR(10);
    DECLARE moneyDecimalValue int;

    -- SELECT f_formatMoneySum(19876);
#     call sp_debug('moneyValue', moneyValue, 'f_formatMoneySum -0');

    SELECT value INTO currencyLabel FROM app_settings WHERE name = 'currency_label';
    SELECT value INTO moneyDecimal FROM app_settings WHERE name = 'money_decimal';

#     call sp_debug('currencyLabel', currencyLabel, 'f_formatMoneySum -1');

    # SELECT CAST(moneyDecimal as int) INTO moneyDecimalValue;

    SET moneyDecimalValue = CAST(moneyDecimal as UNSIGNED INTEGER );
#     call sp_debug('moneyDecimalValue', moneyDecimalValue, 'f_formatMoneySum -2');

    IF(moneyDecimalValue <= 0) THEN
#         call sp_debug('moneyDecimalValue', moneyDecimalValue, 'f_formatMoneySum -3');
        RETURN CONCAT(moneyValue, ' ', currencyLabel);
    END IF;

#    call sp_debug('-7', FORMAT(moneyValue/moneyDecimalValue*10, 2), 'f_formatMoneySum -7');
    RETURN CONCAT(FORMAT(moneyValue/POW(10,moneyDecimalValue), 2), ' ', currencyLabel);

END
";
        DB::unprepared("DROP FUNCTION IF EXISTS f_formatMoneySum");
        DB::unprepared($procedure);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP FUNCTION IF EXISTS f_formatMoneySum");
    }
};
