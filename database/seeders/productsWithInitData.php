<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\CityProduct;
use DB;

class productsWithInitData extends Seeder
{
    /**
     * Run the database seeds.
     *   php artisan db:seed productsWithInitData
     *
     * @return void
     */
    public function run()
    {
        \DB::table('products')->delete();
        $chunksNumber = ProductsFactoryCount; // 0
        $rowsNumber = 1; // 300
//        $chunksNumber = 5;
//        $rowsNumber = 2;
        $faker = \Faker\Factory::create();

        \Log::info(varDump(mt_getrandmax(), ' -1 mt_getrandmax()::'));
        for ($i = 0; $i < $chunksNumber; $i++) {
//            \Log::info(varDump($i, ' -1 $i::'));
            $data = [];
            for ($j = 0; $j < $rowsNumber; $j++) { // Rows inside of 1 chunk
//                \Log::info(varDump($j, ' -2 $j::'));
                $title = 'Product '.$faker->text(100);
                $status = 'A';    // D => Draft, P=>Pending Review, A=>Active, I=>Inactive'
                $rand = rand(1, 10);
                $discountPriceAllowed = $rand >= 3;
                if ($rand == 1) {
                    $status = 'D'; // Draft
                }
                if ($rand == 2) {
                    $status = 'P'; // Pending Review
                }
                if ($rand == 3) {
                    $status = 'I'; // Inactive
                }
                $publishedAt = null;
                if ($status === 'A') { // Only Active has value in published_at
                    $publishedAt = $faker->dateTimeBetween(startDate: '-200 days', endDate: 'now');
                }

                $salePrice = $faker->biasedNumberBetween(10, 2400) * 10;
                $regularPrice = $salePrice; // The “Regular Price” is the normal price for your product.
                if ($discountPriceAllowed) {
                    // The “Sale Price” is a price for if you are discounting from your “Regular Price”.
                    $salePrice = $regularPrice - round($salePrice * 0.1, 2);
                }


                $inStock = true;
                $rand = rand(1, 10);
                $stockQty = $faker->biasedNumberBetween(1, 500);

                if ($rand === 1) {
                    $inStock = false;
                    $stockQty = 0;
                }

                $isFeatured = false;
                $rand = rand(1, 10);
                if ($rand === 1) {
                    $isFeatured = true;
                }
                $descriptionText = 'Product description '.$faker->text;
                $shortDescriptionText = 'Short product description '.$faker->text;
                $slugText = SlugService::createSlug(Product::class, 'slug', $title);

                $categoryId = $faker->randomElement(Category::all())['id'];

                $data[] = [
                    'user_id' => $faker->randomElement(User::all())['id'],
                    'brand_id' => $faker->randomElement(Brand::all())['id'],
                    'title' => $title,
                    'status' => $status,
                    'category_id' => $categoryId,
                    'regular_price' => $regularPrice,
                    'sale_price' => $salePrice,
                    'in_stock' => $inStock,
                    'stock_qty' => $stockQty,
                    'discount_price_allowed' => $discountPriceAllowed,
                    'is_featured' => $isFeatured,
                    'description' => $descriptionText,
                    'short_description' => $shortDescriptionText,
                    'slug' => $slugText,
                    'sku' => $faker->word.'_'.round(microtime(true) * 1000),
                    'published_at' => $publishedAt,
                    'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
                ];
            } // Rows inside of 1 chunk

            $chunksData = array_chunk($data, $chunksNumber);
            foreach ($chunksData as $nextChunksData) {
                Product::insert($nextChunksData);
            }
        } // // for ($j = 0; $j < $rowsNumber; $j++) { // Rows inside of 1 chunk
        Product::orderBy('id', 'asc')
            ->chunk(50, function ($chunkedProducts) use ($faker) {
                foreach ($chunkedProducts as $nextProduct) {
//                    \Log::info(varDump($categories, ' -1 $categories::'));
/*                    foreach ($categories as $nextCategory) {
//                            \Log::info(  varDump($nextCategory, ' -12 $nextCategory::') );
                        $productCategory = CategoryProduct
                            ::getByProductId($nextProduct->id)
                            ->getByCategoryId($nextCategory->id)
                            ->first();
                        if (empty($productCategory)) {
                            CategoryProduct::create([
                                'product_id' => $nextProduct->id,
                                'category_id' => $nextCategory->id,
                            ]);
                        }
                    } // foreach( $categories as $nextCategory ) {*/

                    $cities = $faker->randomElements(City::all(), rand(1, 3));
//                    \Log::info(varDump($cities, ' -1 $cities::'));
                    foreach ($cities as $nextCity) {
                        $productCity = CityProduct
                            ::getByProductId($nextProduct->id)
                            ->getByCityId($nextCity->id)
                            ->first();
                        if (empty($productCity)) {
                            CityProduct::insert([
                                'product_id' => $nextProduct->id,
                                'city_id' => $nextCity->id,
                            ]);
                        }
                    } // foreach( $cities as $nextCity ) {
                }
            }); /// Product           ::orderBy('id', 'asc')      ->chunk
    } // for ($i = 0; $i < $chunksNumber; $i++) {
}
