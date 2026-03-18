<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\ShortVideo;
use App\Models\ShortVideoSave;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ShortVideoSave>
 */
class ShortVideoSaveFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'short_video_id' => ShortVideo::factory(),
            'user_id' => User::factory(),
        ];
    }
}
