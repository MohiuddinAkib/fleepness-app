<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'delivery_option_id' => null,
            'order_number' => strtoupper(Str::random(12)),
            'is_multi_vendor' => false,
            'vendor_count' => 1,
            'product_total' => '0.00',
            'delivery_fee' => '0.00',
            'platform_fee' => '0.00',
            'vat' => '0.00',
            'commission' => '0.00',
            'grand_total' => '0.00',
            'balance' => '0.00',
            'is_completed' => false,
        ];
    }
}
