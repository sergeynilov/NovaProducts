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
CREATE PROCEDURE sp_debug(
    IN in_label varchar(100),
    IN in_value varchar(1000),
    IN in_source varchar(100)
)
BEGIN

    INSERT INTO data_debug(label, value, source) VALUES(in_label, in_value, in_source);

END;
";
        DB::unprepared("DROP procedure IF EXISTS sp_debug");
        DB::unprepared($procedure);


        ////////////
        $procedure = "
CREATE PROCEDURE sp_clear_debug()
BEGIN

    DELETE FROM data_debug;

END;
";
        DB::unprepared("DROP procedure IF EXISTS sp_clear_debug");
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        return;
        DB::unprepared("DROP procedure IF EXISTS sp_clear_debug");
    }
};
