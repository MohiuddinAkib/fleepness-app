<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ShortVideo;
use App\Models\VendorProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ShortVideo>
 */
class ShortVideoFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'vendor_profile_id' => VendorProfile::factory()->approved(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'likes_count' => 0,
        ];
    }
}
