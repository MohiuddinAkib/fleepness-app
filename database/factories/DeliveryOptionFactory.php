<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\DeliveryOption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DeliveryOption>
 */
class DeliveryOptionFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'estimated_minutes' => fake()->numberBetween(30, 180),
            'fee' => fake()->randomFloat(2, 20, 150),
            'is_active' => true,
        ];
    }
}
