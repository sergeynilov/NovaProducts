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
        Schema::create('data_debug', function (Blueprint $table) {
            $table->id();
            $table->string('label', 100);
            $table->string('value', 1000)->nullable();
            $table->string('source', 100)->nullable();
            $table->index(['source']);

            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_debug');
    }
};
