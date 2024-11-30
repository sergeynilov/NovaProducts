<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class usersWithInitData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        \DB::table('users')->delete();

        \DB::table('users')->insert([ // id = 1
            'name'     => 'JohnGlads',
            'email'    => 'admin@site.com',
            'status'   => 'A',
            'password' => Hash::make('111111'),
            'avatar' => 'public/avatars/boss.avif',
            'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
        ]);
        \DB::table('users')->insert([
            'name'     => 'RodBodrick',
            'email'    => 'nilov@softreactor.com',
            'status'   => 'A',
            'password' => Hash::make('111111'),
            'avatar' => 'public/avatars/RodBodrick.jpg',
            'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
        ]);

        \DB::table('users')->insert([
            'id' => 3,
            'name' => 'TonyBlack',
            'status'   => 'B',
            'email' => 'tony_black@site.com',
            'password' => Hash::make('111111'),
            'avatar' => 'public/avatars/TonyBlack.jpg',
            'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
        ]);
        \DB::table('users')->insert([
            'id' => 4,
            'name' => 'AdamLang',
            'email' => 'adam_lang@site.com',
            'status'   => 'A',
            'password' => Hash::make('111111'),
            'avatar' => 'public/avatars/AdamLang.avif',
            'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
        ]);


        \DB::table('users')->insert([
            'id' => 5,
            'name' => 'NagelPodell',
            'email' => 'demo@mail.com',
            'status'   => 'A',
            'password' => Hash::make('1t11a1d1s1'),
            'avatar' => 'public/avatars/NagelPodell.png',
            'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
        ]);
        \DB::table('users')->insert([
            'id' => 6,
            'name' => 'SamHonskel',
            'email' => 'nilovsergey@yahoo.com',
            'status'   => 'I',
            'password' => Hash::make('111111'),
            'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
        ]);

        User::factory()->count(10)->create();
    }
}
