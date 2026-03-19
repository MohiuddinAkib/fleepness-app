<?php

declare(strict_types=1);

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Enums\VendorStatus;
use App\Models\VendorOrder;
use App\Models\VendorProfile;
use App\Enums\VendorOrderStatus;
use App\Filament\Widgets\CommerceOverviewWidget;
use App\Filament\Widgets\OrdersTrendChartWidget;
use App\Filament\Widgets\MarketplaceHealthWidget;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

it('builds commerce overview widget stats from marketplace data', function (): void {
    Order::factory()->count(2)->create(['grand_total' => '125.50']);
    $vendorProfile = VendorProfile::factory()->approved()->create();
    $customer = User::factory()->create();
    $order = Order::factory()->create();

    VendorOrder::factory()->create([
        'order_id' => $order->getKey(),
        'vendor_profile_id' => $vendorProfile->getKey(),
        'customer_id' => $customer->getKey(),
        'status' => VendorOrderStatus::Delivered,
    ]);
    VendorOrder::factory()->create([
        'order_id' => $order->getKey(),
        'vendor_profile_id' => $vendorProfile->getKey(),
        'customer_id' => $customer->getKey(),
        'status' => VendorOrderStatus::Pending,
    ]);

    $widget = new class extends CommerceOverviewWidget
    {
        public function exposeStats(): array
        {
            return $this->getStats();
        }
    };

    $stats = $widget->exposeStats();

    expect($stats)->toHaveCount(4)
        ->and($stats[0]->getValue())->toBe('3')
        ->and($stats[1]->getValue())->toBe('1')
        ->and($stats[2]->getValue())->toBe('1')
        ->and($stats[3]->getValue())->toBe('$251.00');
});

it('builds marketplace health widget stats from vendor and product review state', function (): void {
    VendorProfile::factory()->approved()->count(2)->create();
    VendorProfile::factory()->create(['status' => VendorStatus::Pending]);
    $vendorProfile = VendorProfile::factory()->approved()->create();

    Product::factory()->count(3)->create([
        'vendor_profile_id' => $vendorProfile->getKey(),
        'is_approved' => true,
    ]);
    Product::factory()->count(2)->unapproved()->create([
        'vendor_profile_id' => $vendorProfile->getKey(),
    ]);

    $widget = new class extends MarketplaceHealthWidget
    {
        public function exposeStats(): array
        {
            return $this->getStats();
        }
    };

    $stats = $widget->exposeStats();

    expect($stats)->toHaveCount(4)
        ->and($stats[0]->getValue())->toBe('3')
        ->and($stats[1]->getValue())->toBe('1')
        ->and($stats[2]->getValue())->toBe('3')
        ->and($stats[3]->getValue())->toBe('2');
});

it('builds the orders trend chart widget for the last seven days', function (): void {
    Order::factory()->create([
        'grand_total' => '200.00',
        'created_at' => now()->subDays(1),
    ]);
    Order::factory()->create([
        'grand_total' => '75.00',
        'created_at' => now()->subDays(1),
    ]);
    Order::factory()->create([
        'grand_total' => '150.00',
        'created_at' => now()->subDays(3),
    ]);

    $widget = new class extends OrdersTrendChartWidget
    {
        public function exposeData(): array
        {
            return $this->getData();
        }

        public function exposeType(): string
        {
            return $this->getType();
        }
    };

    $data = $widget->exposeData();

    expect($widget->exposeType())->toBe('line')
        ->and($data['labels'])->toHaveCount(7)
        ->and($data['datasets'])->toHaveCount(2)
        ->and($data['datasets'][0]['label'])->toBe('Orders')
        ->and($data['datasets'][1]['label'])->toBe('Revenue');
});
