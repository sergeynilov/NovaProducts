<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->smallIncrements('id')->unsigned();

            $table->string('address',100);
            $table->string('postal_code',10)->nullable();
            $table->string('country',50);
            $table->string('region',50);

            $table->decimal('geo_lat',16,7);
            $table->decimal('geo_lon',16,7);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['postal_code'], 'cities_postal_code_index');
            $table->index(['country', 'region'], 'cities_country_region_index');
        });

        \Artisan::call('db:seed', array('--class' => 'CitiesTableSeeder'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
