<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->smallIncrements('id')->unsigned();
            $table->string('name',50)->unique();

            $table->unsignedSmallInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('categories')->onUpdate('RESTRICT')->onDelete('CASCADE');

            $table->boolean('active')->default(false);
            $table->string('slug', 55)->unique();
            $table->mediumText('description');
            $table->timestamps();

            $table->index(['active', 'name'], 'categories_active_name_index');
            $table->index(['id', 'active', 'name'], 'categories_id_active_name_index');

        });

        \Artisan::call('db:seed', array('--class' => 'CategoryTableSeeder'));


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
