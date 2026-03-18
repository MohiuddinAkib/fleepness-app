<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\Order;
use App\Models\VendorOrder;
use Illuminate\Support\Str;
use App\Models\VendorProfile;
use App\Enums\VendorOrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VendorOrder>
 */
class VendorOrderFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'vendor_profile_id' => VendorProfile::factory()->approved(),
            'customer_id' => User::factory(),
            'order_number' => strtoupper(Str::random(12)),
            'status' => VendorOrderStatus::Pending,
            'product_total' => '0.00',
            'commission' => '0.00',
            'vat' => '0.00',
            'delivery_fee' => '0.00',
            'balance' => '0.00',
            'is_rider_assigned' => false,
            'is_delayed' => false,
        ];
    }
}
