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
        Schema::table('orders', function (Blueprint $table) {
            $table->fullText('billing_company' );
            $table->fullText('billing_phone' );
            $table->fullText('billing_email' );
            $table->fullText('billing_country' );
            $table->fullText('billing_address' );
            $table->fullText('billing_address2' );
            $table->fullText('billing_city' );
            $table->fullText('billing_postcode' );
            $table->fullText('info' );
            $table->fullText('order_number' );
        });
    }

};
