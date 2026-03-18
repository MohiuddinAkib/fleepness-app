<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\DeviceToken;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DeviceToken>
 */
class DeviceTokenFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'token' => Str::uuid()->toString(),
            'platform' => fake()->randomElement(['android', 'ios']),
        ];
    }
}
