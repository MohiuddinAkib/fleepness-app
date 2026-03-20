<?php

declare(strict_types=1);

namespace App\Enums;

enum BroadcastEvent: string
{
    case NewOrderForVendor = 'new_order_for_vendor';
    case CustomerOrderStatusChanged = 'customer_order_status_changed';
    case VendorApplicationStatusUpdated = 'vendor_application_status_updated';
    case WithdrawalRequestApproved = 'withdrawal_request_approved';
    case LivestreamCreated = 'livestream_created';
    case LivestreamUpdated = 'livestream_updated';
    case LivestreamCommentCreated = 'livestream_comment_created';
    case LivestreamCommentDeleted = 'livestream_comment_deleted';
    case LivestreamLikeCountUpdated = 'livestream_like_count_updated';
}
