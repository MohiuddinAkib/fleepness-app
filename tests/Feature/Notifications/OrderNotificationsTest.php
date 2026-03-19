<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\VendorOrder;
use App\Models\VendorProfile;
use App\Enums\VendorOrderStatus;
use App\Notifications\OrderReceivedByVendor;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VendorOrderStatusChanged;

it('sends OrderReceivedByVendor notification via correct channels', function (): void {
    Notification::fake();

    $vendor = VendorProfile::factory()->approved()->create();
    $vendorOrder = VendorOrder::factory()->create(['vendor_profile_id' => $vendor->getKey()]);

    $vendor->user->notify(new OrderReceivedByVendor($vendorOrder));

    Notification::assertSentTo(
        $vendor->user,
        OrderReceivedByVendor::class,
        function (OrderReceivedByVendor $notification) use ($vendorOrder): bool {
            return $notification->vendorOrder->getKey() === $vendorOrder->getKey()
                && in_array('broadcast', $notification->via($notification->vendorOrder->vendorProfile->user), true)
                && in_array('fcm-device', $notification->via($notification->vendorOrder->vendorProfile->user), true)
                && in_array('database', $notification->via($notification->vendorOrder->vendorProfile->user), true);
        }
    );
});

it('OrderReceivedByVendor has correct broadcast payload', function (): void {
    $vendor = VendorProfile::factory()->approved()->create();
    $vendorOrder = VendorOrder::factory()->create(['vendor_profile_id' => $vendor->getKey()]);

    $notification = new OrderReceivedByVendor($vendorOrder);
    $payload = $notification->toBroadcast($vendor->user);

    expect($payload)->toHaveKey('vendor_order')
        ->and($payload['vendor_order'])->toHaveKey('order_number');
});

it('sends VendorOrderStatusChanged notification via correct channels', function (): void {
    Notification::fake();

    $customer = User::factory()->create();
    $vendorOrder = VendorOrder::factory()->create([
        'customer_id' => $customer->getKey(),
        'status' => VendorOrderStatus::Packaging,
    ]);

    $customer->notify(new VendorOrderStatusChanged($vendorOrder));

    Notification::assertSentTo(
        $customer,
        VendorOrderStatusChanged::class,
        function (VendorOrderStatusChanged $notification) use ($vendorOrder, $customer): bool {
            return $notification->vendorOrder->getKey() === $vendorOrder->getKey()
                && in_array('broadcast', $notification->via($customer), true)
                && in_array('database', $notification->via($customer), true);
        }
    );
});

it('VendorOrderStatusChanged is not sent for pending orders', function (): void {
    $customer = User::factory()->create();
    $vendorOrder = VendorOrder::factory()->create([
        'customer_id' => $customer->getKey(),
        'status' => VendorOrderStatus::Pending,
    ]);

    $notification = new VendorOrderStatusChanged($vendorOrder);

    expect($notification->shouldSend($customer, 'broadcast'))->toBeFalse();
});

it('VendorOrderStatusChanged has correct database payload', function (): void {
    $customer = User::factory()->create();
    $vendorOrder = VendorOrder::factory()->create([
        'customer_id' => $customer->getKey(),
        'status' => VendorOrderStatus::Delivered,
    ]);

    $notification = new VendorOrderStatusChanged($vendorOrder);
    $payload = $notification->toArray($customer);

    expect($notification->broadcastAs())->toBe('customer_order_status_changed')
        ->and($payload['type'])->toBe('customer_order_status_changed')
        ->and($payload['status'])->toBe(VendorOrderStatus::Delivered->value);
});

it('OrderReceivedByVendor uses the canonical event name', function (): void {
    $vendor = VendorProfile::factory()->approved()->create();
    $vendorOrder = VendorOrder::factory()->create(['vendor_profile_id' => $vendor->getKey()]);

    $notification = new OrderReceivedByVendor($vendorOrder);

    expect($notification->broadcastAs())->toBe('new_order_for_vendor')
        ->and($notification->toArray($vendor->user)['type'])->toBe('new_order_for_vendor');
});
