<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Livestream;
use Illuminate\Support\Str;
use App\Models\VendorProfile;
use App\Enums\LivestreamStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Livestream>
 */
class LivestreamFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'vendor_profile_id' => VendorProfile::factory()->approved(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'room_id' => Str::uuid()->toString(),
            'status' => LivestreamStatus::Scheduled,
            'viewer_count' => 0,
            'scheduled_at' => now()->addHour(),
        ];
    }

    public function started(): static
    {
        return $this->state([
            'status' => LivestreamStatus::Started,
            'started_at' => now(),
        ]);
    }

    public function finished(): static
    {
        return $this->state([
            'status' => LivestreamStatus::Finished,
            'started_at' => now()->subHour(),
            'ended_at' => now(),
            'total_duration' => 3600,
        ]);
    }
}
