<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\Livestream;
use App\Models\LivestreamComment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LivestreamComment>
 */
class LivestreamCommentFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'livestream_id' => Livestream::factory(),
            'user_id' => User::factory(),
            'comment' => fake()->sentence(),
        ];
    }
}
