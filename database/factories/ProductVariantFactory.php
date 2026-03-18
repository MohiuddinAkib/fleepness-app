<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'name' => fake()->randomElement(['S', 'M', 'L', 'XL', 'XXL']),
            'price' => fake()->randomFloat(2, 50, 5000),
            'stock' => fake()->numberBetween(0, 50),
        ];
    }
}
