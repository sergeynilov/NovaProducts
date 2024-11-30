<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ActionNote;
use Illuminate\Database\Seeder;

class actionNotesWithInitData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        ActionNote::factory()->count(User::count() * 1)->create();
    }
}
