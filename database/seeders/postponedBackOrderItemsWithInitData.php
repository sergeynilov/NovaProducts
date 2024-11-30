<?php

namespace Database\Seeders;

use App\Models\PostponedBackOrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class postponedBackOrderItemsWithInitData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PostponedBackOrderItem::factory()->count( round(ProductsFactoryCount / 3, 0)  )->create();
    }
}
