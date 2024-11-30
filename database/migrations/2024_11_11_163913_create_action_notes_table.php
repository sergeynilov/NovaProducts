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
        Schema::create('action_notes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->references('id')->on('users')->onDelete('CASCADE');
            $table->morphs('model');

            $table->string('note_type', 30)->nullable();
            $table->mediumText('note')->nullable();

            $table->index(['model_type', 'model_id', 'note_type'], 'action_notes_3fields_index');
            $table->index(['user_id', 'note_type'], 'action_notes_user_id_note_type_index');
//            $table->unique(['model_type', 'model_id', 'user_id', 'action'], 'reactions_4fields_unique');

            $table->timestamp('created_at')->useCurrent();
        });
        \Artisan::call('db:seed', array('--class' => 'actionNotesWithInitData'));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_notes');
    }
};
