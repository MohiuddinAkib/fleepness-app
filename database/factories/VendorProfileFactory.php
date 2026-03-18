<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Enums\VendorStatus;
use App\Models\VendorProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VendorProfile>
 */
class VendorProfileFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'shop_name' => fake()->company(),
            'description' => fake()->sentence(),
            'balance' => '0.00',
            'total_sales' => '0.00',
            'withdrawn_amount' => '0.00',
            'order_count' => 0,
            'status' => VendorStatus::Pending,
        ];
    }

    public function approved(): static
    {
        return $this->state(['status' => VendorStatus::Approved]);
    }

    public function rejected(): static
    {
        return $this->state(['status' => VendorStatus::Rejected]);
    }
}
