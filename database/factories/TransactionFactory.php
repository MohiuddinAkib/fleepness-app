<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\Transaction;
use App\Enums\TransactionType;
use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'amount' => fake()->randomFloat(2, 10, 10000),
            'type' => TransactionType::Withdrawal,
            'status' => TransactionStatus::Pending,
        ];
    }

    public function approved(): static
    {
        return $this->state(['status' => TransactionStatus::Approved]);
    }

    public function rejected(): static
    {
        return $this->state(['status' => TransactionStatus::Rejected]);
    }
}
