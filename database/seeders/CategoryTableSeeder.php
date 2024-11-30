<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        \DB::table('categories')->delete();

        \DB::table('categories')->insert(array(
            array(
                'id' => 1,
                'name' => 'Computers',
                'parent_id' => null,  // Root category
                'active' => 1,
                'slug' => 'computers',
                'description' => '<p><strong>Computers accessories group</strong> description </p><p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat.  </p>',
                'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
            ),

            array(
                'id' => 2,
                'name' => 'Laptops',
                'parent_id' => 1,  // Child of Root category 'Computers'
                'active' => 1,
                'slug' => 'laptops',
                'description' => '<p><strong>Laptops</strong> description </p><p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. </p>',
                'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
            ),

            array(
                'id' => 3,
                'name' => 'Computer Monitor',
                'parent_id' => 1,  // Child of Root category 'Computers'
                'active' => 1,
                'slug' => 'computer-monitor',
                'description' => '<p><strong>Computer Monitor</strong> description </p><p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla... <p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla... </p><p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla... </p><ul>
    <li>1st point lorem ipsum</li>
    <li>2nd point lorem ipsum</li>
    <li>3rd point lorem ipsum</li>
</ul></p>',
                'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
            ),

            array(
                'id' => 4,
                'name' => 'Computer Mouse',
                'parent_id' => 1,  // Child of Root category 'Computers'
                'active' => 1,
                'slug' => 'computer-mouse',
                'description' => '<p><strong>Computer Mouse</strong> description </p><p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. </p>',
                'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
            ),

            array(
                'id' => 5,
                'name' => 'Computer Accessories',
                'active' => 1,
                'parent_id' => 1,  // Child of Root category 'Computers'
                'slug' => 'computer-accessories',
                'description' => '<p><strong>Computer Accessories</strong> description </p><p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla... <p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla... </p><p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla... </p><ul>
    <li>1st point lorem ipsum</li>
    <li>2nd point lorem ipsum</li>
    <li>3rd point lorem ipsum</li>
</ul></p>',
                'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
            ),


            array(
                'id' => 6,
                'name' => 'Video equipment',
                'parent_id' => null,  // Root category
                'active' => 1,
                'slug' => 'video-equipment',
                'description' => '<p><strong>Video equipment</strong> description </p><p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. </p>',
                'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
            ),

            array(
                'id' => 7,
                'name' => 'Digital Cameras',
                'parent_id' => 6,  // Child of Root category 'Video equipment'
                'active' => 1,
                'slug' => 'digital-cameras',
                'description' => '<p><strong>Digital Cameras</strong> description </p><p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. </p>',
                'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
            ),

            array(
                'id' => 8,
                'name' => 'PC Gaming',
                'parent_id' => 1,  // Child of Root category 'Computers'
                'active' => 1,
                'slug' => 'pc-gaming',
                'description' => '<p><strong>PC Gaming</strong> description </p><p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat</p>',
                'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
            ),

            array(
                'id' => 9,
                'name' => 'ThinkPad',
                'parent_id' => 1,  // Child of Root category 'Computers'

                'active' => 1,
                'slug' => 'think-pad',
                'description' => '<p><strong>ThinkPad</strong> description </p><p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla... <p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla... </p><p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla... </p><ul>
    <li>1st point lorem ipsum</li>
    <li>2nd point lorem ipsum</li>
    <li>3rd point lorem ipsum</li>
</ul></p>',
                'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
            ),

                array(
                    'id' => 10,
                    'name' => 'News',
                    'parent_id' => null,  // Root category
                    'active' => 1,
                    'slug' => 'news',
                    'description' => '<p><strong>news</strong> description </p><p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat. </p>',
                    'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
                ),

                array(
                    'id' => 11,
                    'parent_id' => 10,  // Child of Root category 'News'
                    'name' => 'News of site',
                    'active' => 1,
                    'slug' => 'news-of-site',
                    'description' => '<p><strong>News of site</strong> description </p><p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat</p>',
                    'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
                ),

                array(
                    'id' => 12,
                    'parent_id' => 10,  // Child of Root category 'News'
                    'name' => 'Sport News',
                    'active' => 1,
                    'slug' => 'sport-news',
                    'description' => '<p><strong>Sport News</strong> description </p><p>Lorem <strong>ipsum dolor sit</strong> amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis <strong>nostrud exercitation</strong> ullamco laboris nisi ut aliquip ex ea  commodo consequat</p>',
                    'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
                ),

//                'created_at' => $faker->dateTimeBetween('-1 month', '-1 hour'),
        ));
    }
}
