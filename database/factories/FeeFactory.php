<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Fee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Fee>
 */
class FeeFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'vat' => '15.00',
            'platform_fee' => '10.00',
            'commission' => '5.00',
        ];
    }
}
