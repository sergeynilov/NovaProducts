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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');

            $table->string('billing_first_name', 50)->nullable();
            $table->string('billing_last_name', 50)->nullable();
            $table->string('billing_company', 100)->nullable();
            $table->string('billing_phone', 20)->nullable();
            $table->string('billing_email', 100)->nullable();
            $table->char('billing_country', 2)->nullable();
            $table->string('billing_address', 100)->nullable();
            $table->string('billing_address2', 100)->nullable();
            $table->string('billing_city', 50)->nullable();
            $table->string('billing_state', 100)->nullable();
            $table->char('billing_postcode', 6)->nullable();

            $table->mediumText('info')->nullable();
            $table->integer('price_summary')->unsigned()->comment('Cast on client must be used - Money sum = value/ 100');
            $table->smallInteger('items_quality')->unsigned();

            $table->string('payment', 2)->nullable();
            $table->string('currency', 3)->nullable();
            $table->enum('status',
                ['D', 'I', 'C', 'P', 'O', 'R'])->comment('D - Draft, I-Invoice, C-Cancelled, P-Processing, O - Completed, R - Refunded');

            $table->string('order_number', 15);
            $table->boolean('other_shipping');
            $table->string('payment_client_ip', 15)->nullable();
            $table->date('last_operation_date')->nullable();
            $table->date('expires_at')->nullable();
            $table->enum('mode', ['T', 'L'])->comment('T - , L -');

            $table->foreignId('manager_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->datetime('completed_by_manager_at')->nullable();
            $table->timestamps();

            $table->index(['creator_id', 'status', 'payment', 'currency', 'order_number'], 'orders_5fields_index');
            $table->index(['creator_id', 'status', 'manager_id', 'completed_by_manager_at'], 'orders_4fields_index');
            $table->index(['expires_at', 'status', 'creator_id'], 'orders_expires_at_status_creator_id _index');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
