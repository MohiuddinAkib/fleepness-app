<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SizeTemplate;
use App\Models\VendorProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SizeTemplate>
 */
class SizeTemplateFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'vendor_profile_id' => VendorProfile::factory()->approved(),
            'name' => fake()->words(2, true),
        ];
    }
}
