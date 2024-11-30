<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class brandsWithInitData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        \DB::table('brands')->delete();

        \DB::table('brands')->insert(array(
            array(
                'id' => 1,
                'name' => 'Alfa brand',
                'website' => 'https://alfa-brand.com',
                'image' => 'public/brands/oDTvcZgLAydSTYxvJWg3DwQNgwHQKEPKSst9LDYC.jpg',
                'active' => 1,
                'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
            ),

        ));
        \DB::table('brands')->insert(array(
            array(
                'id' => 2,
                'name' => 'Beta brand',
                'website' => 'https://beta-brand.com',
                'image' => 'public/brands/z7HyH5CpgAS3gK0w46mrHGYvkUZ16RCur5MSxQuU.png',
                'active' => 1,
                'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
            ),

        ));
        \DB::table('brands')->insert(array(
            array(
                'id' => 3,
                'name' => 'Gamma brand',
                'website' => 'https://gamma-brand.com',
                'image' => 'public/brands/2gqo5g5zjegmvsWhj48ABFtj5MpjK5P7FD7qyrbu.png',
                'active' => 1,
                'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
            ),

        ));
    }
}
