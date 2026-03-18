<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CartItem>
 */
class CartItemFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'product_variant_id' => null,
            'quantity' => fake()->numberBetween(1, 5),
            'is_selected' => true,
        ];
    }

    public function unselected(): static
    {
        return $this->state(['is_selected' => false]);
    }
}
