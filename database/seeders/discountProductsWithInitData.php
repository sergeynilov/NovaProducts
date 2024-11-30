<?php

namespace Database\Seeders;

use App\Models\Discount;
use App\Models\Product;
use Illuminate\Database\Seeder;

class discountProductsWithInitData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('discount_product')->delete();
        $faker = \Faker\Factory::create();
        $discountIds = Discount::all()->pluck('id');
//        \Log::info(varDump($discountIds, ' -10 discountProductsWithInitData $discountIds::'));
        foreach (Product::get() as $product) {
            if ( ! $product->getAttribute('discount_price_allowed')) {
                continue;
            }
            $rand = rand(1, 4);
            if ($rand === 1) {
                continue;
            }
            $discountId = $faker->randomElement($discountIds);
            $product->discounts()->attach($discountId);
        }
    }
}
