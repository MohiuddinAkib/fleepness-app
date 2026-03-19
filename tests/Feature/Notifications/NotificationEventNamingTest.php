<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Livestream;
use App\Models\Transaction;
use App\Models\VendorOrder;
use App\Models\VendorProfile;
use App\Models\LivestreamLike;
use App\Enums\TransactionStatus;
use App\Enums\VendorOrderStatus;
use App\Models\LivestreamComment;
use App\Notifications\WithdrawalApproved;
use App\Notifications\VendorStatusUpdated;
use App\Notifications\OrderReceivedByVendor;
use App\Notifications\VendorOrderStatusChanged;

beforeEach(function (): void {
    config()->set('broadcasting.default', 'log');
});

it('uses the canonical event names for current notifications', function (): void {
    $user = User::factory()->create();
    $vendorProfile = VendorProfile::factory()->approved()->create();
    $vendorOrder = VendorOrder::factory()->create([
        'vendor_profile_id' => $vendorProfile->getKey(),
        'customer_id' => $user->getKey(),
        'status' => VendorOrderStatus::Delivered,
    ]);
    $transaction = Transaction::factory()->create([
        'user_id' => $user->getKey(),
        'status' => TransactionStatus::Approved,
    ]);
    $livestream = Livestream::factory()->started()->create();
    $comment = LivestreamComment::factory()->create([
        'livestream_id' => $livestream->getKey(),
        'user_id' => $user->getKey(),
    ]);
    $like = LivestreamLike::factory()->create([
        'livestream_id' => $livestream->getKey(),
        'user_id' => $user->getKey(),
    ]);

    expect((new OrderReceivedByVendor($vendorOrder))->broadcastAs())->toBe('new_order_for_vendor')
        ->and((new VendorOrderStatusChanged($vendorOrder))->broadcastAs())->toBe('customer_order_status_changed')
        ->and(VendorStatusUpdated::approved()->broadcastAs())->toBe('vendor_application_status_updated')
        ->and((new WithdrawalApproved($transaction))->broadcastAs())->toBe('withdrawal_request_approved')
        ->and($comment->broadcastAs('created'))->toBe('livestream_comment_created')
        ->and($like->broadcastAs('created'))->toBe('livestream_like_count_updated');
});
