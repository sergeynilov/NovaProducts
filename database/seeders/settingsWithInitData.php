<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class settingsWithInitData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('app_settings')->insert([
            'name' => 'site_name',
            'value' =>  'Products E-Shop',
        ]);

        \DB::table('app_settings')->insert([
            'name' => 'copyright_text',
            'value' =>  '© 2023 - 2024 All rights reserved',
        ]);

        \DB::table('app_settings')->insert([
            'name' => 'currency_label',
            'value' =>  'USD',
        ]);
        \DB::table('app_settings')->insert([
            'name' => 'money_decimal',
            'value' =>  '2',
        ]);

    }
}
