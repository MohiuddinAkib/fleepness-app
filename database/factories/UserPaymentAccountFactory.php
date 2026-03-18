<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\PaymentMethod;
use App\Models\UserPaymentAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserPaymentAccount>
 */
class UserPaymentAccountFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'payment_method_id' => PaymentMethod::factory(),
            'account_number' => fake()->numerify('01#########'),
            'is_primary' => false,
        ];
    }

    public function primary(): static
    {
        return $this->state(['is_primary' => true]);
    }
}
