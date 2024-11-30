<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductAttribute>
 */
class ProductAttributeFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $propertyKey = $this->faker->randomElement(['level', 'quality', 'top', 'new', 'seller', 'remark', 'bestseller']);
        $propertyValue = $propertyKey.' '.$this->faker->name;

        return [
            'product_id' => $this->faker->randomElement(Product::all())['id'],
            'key' => $propertyKey,
            'value' => $propertyValue,
            'created_at' => $this->faker->dateTimeBetween('-1 month', '-1 hour'),
        ];
    }

}
