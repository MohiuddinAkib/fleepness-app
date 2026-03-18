<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\VendorOrder;
use App\Models\VendorOrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VendorOrderItem>
 */
class VendorOrderItemFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        $unitPrice = fake()->randomFloat(2, 100, 2000);
        $quantity = fake()->numberBetween(1, 5);

        return [
            'vendor_order_id' => VendorOrder::factory(),
            'product_id' => Product::factory(),
            'product_variant_id' => null,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => round($unitPrice * $quantity, 2),
        ];
    }
}
