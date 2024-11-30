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
        Schema::create('order_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->references('id')->on('orders')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('creator_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');

            $table->string('operation_type', 20)->nullable();
            $table->enum('before_status',
                ['D', 'I', 'C', 'P', 'O', 'R'])->comment('D - Draft, I-Invoice, C-Cancelled, P-Processing, O - Completed, R - Refunded')->nullable();
            $table->enum('status',
                ['D', 'I', 'C', 'P', 'O', 'R'])->comment('D - Draft, I-Invoice, C-Cancelled, P-Processing, O - Completed, R - Refunded');
            $table->tinyText('info')->nullable();
            $table->tinyText('error_info')->nullable();
            $table->string('ip_address', 20);

            $table->index(['order_id', 'creator_id', 'operation_type', 'status'], 'order_operations_4fields_index');

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_operation');
    }
};
