<?php

namespace Database\Seeders;

use App\Enums\NovaSettingsParamEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class novaSettingsWithInitData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('nova_settings')->delete();

        \DB::table('nova_settings')->insert(array(
            array(
                'key' => NovaSettingsParamEnum::USER_ACTIVE_ON_REGISTER,
                'value' => '1',
            ),
            array(
                'key' => NovaSettingsParamEnum::CONTACT_US_EMAIL,
                'value' => 'support@nova-product.com',
            ),
            array(
                'key' => NovaSettingsParamEnum::INVOICE_DAYS_BEFORE_EXPIRE,
                'value' => '14',
            ),
            array(
                'key' => NovaSettingsParamEnum::APP_LANGS,
                'value' => '[{"lang_code":"en","lang_name":"English","is_default":1},{"lang_code":"ua","lang_name":"Ukrainian","is_default":0},{"lang_code":"es","lang_name":"Spain","is_default":0}]'
            ),
            array(
                'key' => NovaSettingsParamEnum::METRIX_CACHING_IN_MINUTES,
                'value' => '10',
            ),
        ));
    }
}
