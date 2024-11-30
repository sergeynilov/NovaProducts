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
        Schema::create('brands', function (Blueprint $table) {
            $table->smallIncrements('id')->unsigned();
            $table->string('name', 100)->unique();
            $table->string('website', 255);
            $table->string('image', 100)->nullable();
            $table->boolean('active');
            $table->timestamps();

            $table->index(['active', 'name'], 'brands_active_name_index');
        });

        \Artisan::call('db:seed', array('--class' => 'brandsWithInitData'));

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
