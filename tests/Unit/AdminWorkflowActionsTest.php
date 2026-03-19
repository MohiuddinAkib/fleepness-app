<?php

declare(strict_types=1);

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Enums\VendorStatus;
use App\Models\VendorOrder;
use App\Enums\ProductStatus;
use App\Models\VendorProfile;
use App\Enums\VendorOrderStatus;
use App\Notifications\VendorStatusUpdated;
use App\Actions\Admin\ApproveProductAction;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VendorOrderStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Admin\MarkVendorOrderDeliveredAction;
use App\Actions\Admin\UpdateVendorApplicationStatusAction;

uses(TestCase::class, RefreshDatabase::class);

it('approves and revokes products through an admin action', function (): void {
    $product = Product::factory()->create([
        'is_approved' => false,
        'status' => ProductStatus::Inactive,
    ]);

    $action = app(ApproveProductAction::class);

    expect($action->execute($product, true)->is_approved)->toBeTrue();
    expect($action->execute($product->fresh(), false)->is_approved)->toBeFalse();
});

it('updates vendor application status and notifies the vendor user', function (): void {
    Notification::fake();

    $vendorProfile = VendorProfile::factory()->create([
        'status' => VendorStatus::Pending,
    ]);

    $action = app(UpdateVendorApplicationStatusAction::class);
    $approvedProfile = $action->execute($vendorProfile, VendorStatus::Approved);

    expect($approvedProfile->status)->toBe(VendorStatus::Approved);
    Notification::assertSentTo($vendorProfile->user, VendorStatusUpdated::class);
});

it('marks a vendor order as delivered and notifies the customer', function (): void {
    Notification::fake();

    $customer = User::factory()->create();
    $vendorOrder = VendorOrder::factory()->create([
        'customer_id' => $customer->getKey(),
        'status' => VendorOrderStatus::OnTheWay,
    ]);

    $action = app(MarkVendorOrderDeliveredAction::class);
    $deliveredOrder = $action->execute($vendorOrder);

    expect($deliveredOrder->status)->toBe(VendorOrderStatus::Delivered);
    Notification::assertSentTo($customer, VendorOrderStatusChanged::class);
});
