<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Enums\ProductStatus;
use App\Models\VendorProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'vendor_profile_id' => VendorProfile::factory()->approved(),
            'name' => fake()->words(3, true),
            'sku' => fake()->bothify('??-####'),
            'quantity' => fake()->numberBetween(1, 100),
            'selling_price' => fake()->randomFloat(2, 100, 10000),
            'status' => ProductStatus::Active,
            'is_approved' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['status' => ProductStatus::Inactive]);
    }

    public function unapproved(): static
    {
        return $this->state(['is_approved' => false]);
    }
}
