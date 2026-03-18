<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\Livestream;
use App\Models\LivestreamLike;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LivestreamLike>
 */
class LivestreamLikeFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'livestream_id' => Livestream::factory(),
            'user_id' => User::factory(),
        ];
    }
}
