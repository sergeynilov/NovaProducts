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
        Schema::create('user_profile', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->onDelete('CASCADE');

            $table->enum('membership_mark',
                ['N', 'M', 'S', 'G'])->default("N")->comment(' N => No membership, M - Member, S=>Silver Membership, G=>Gold Membership');

            $table->string('phone', 100)->nullable();
            $table->string('website', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'membership_mark'], 'user_profile_user_id_membership_mark_index');
        });

        Artisan::call('db:seed', array('--class' => 'userProfilesWithInitData'));

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profile');
    }
};
