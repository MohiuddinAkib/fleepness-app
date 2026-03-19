<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\VendorOrder;
use App\Enums\VendorOrderStatus;
use App\Notifications\VendorOrderStatusChanged;

class MarkVendorOrderDeliveredAction
{
    public function execute(VendorOrder $vendorOrder): VendorOrder
    {
        $vendorOrder->update([
            'status' => VendorOrderStatus::Delivered,
        ]);

        $vendorOrder = $vendorOrder->fresh('customer');
        $vendorOrder->customer?->notify(new VendorOrderStatusChanged($vendorOrder));

        return $vendorOrder;
    }
}
