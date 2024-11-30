<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class userProfilesWithInitData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('user_profile')->delete();

        $faker = \Faker\Factory::create();
        \DB::table('user_profile')->insert([
            'id' => 1,
            'user_id' => 1,
            'membership_mark' =>'G',
                'phone' => $faker->phoneNumber(),
                'website' => $faker->url(),
                'notes' => $faker->paragraphs(rand(1, 4), true),
            'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
        ]);

        \DB::table('user_profile')->insert([
            'id' => 2,
            'user_id' => 2,
            'membership_mark' =>'N',
            'phone' => $faker->phoneNumber(),
            'website' => $faker->url(),
            'notes' => $faker->paragraphs(rand(1, 4), true),
            'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
        ]);

        \DB::table('user_profile')->insert([
            'id' => 3,
            'user_id' => 3,
            'membership_mark' =>'M',
            'phone' => $faker->phoneNumber(),
            'website' => $faker->url(),
            'notes' => $faker->paragraphs(rand(1, 4), true),
            'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
        ]);

        \DB::table('user_profile')->insert([
            'id' => 4,
            'user_id' => 4,
            'membership_mark' =>'S',
            'phone' => $faker->phoneNumber(),
            'website' => $faker->url(),
            'notes' => $faker->paragraphs(rand(1, 4), true),
            'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
        ]);


        \DB::table('user_profile')->insert([
            'id' => 5,
            'user_id' => 5,
            'membership_mark' =>'G',
            'phone' => $faker->phoneNumber(),
            'website' => $faker->url(),
            'notes' => $faker->paragraphs(rand(1, 4), true),
            'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
        ]);
        \DB::table('user_profile')->insert([
            'id' => 6,
            'user_id' => 6,
            'membership_mark' =>'S',
            'phone' => $faker->phoneNumber(),
            'website' => $faker->url(),
            'notes' => $faker->paragraphs(rand(1, 4), true),
            'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
        ]);

        User::factory()->count(10)->create();
    }
}
