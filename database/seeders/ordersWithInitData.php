<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/*
php artisan db:seed ordersWithInitData
 * */
class ordersWithInitData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::factory()->count(ProductsFactoryCount * 3)->create();
    }
}
