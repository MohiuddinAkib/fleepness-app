<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Enums\VendorStatus;
use App\Models\VendorProfile;
use App\Notifications\VendorStatusUpdated;

class UpdateVendorApplicationStatusAction
{
    public function execute(VendorProfile $vendorProfile, VendorStatus $status): VendorProfile
    {
        $vendorProfile->update([
            'status' => $status,
        ]);

        $vendorProfile = $vendorProfile->fresh('user');

        if ($status->isApproved()) {
            $vendorProfile->user?->notify(VendorStatusUpdated::approved());
        }

        if ($status->isRejected()) {
            $vendorProfile->user?->notify(VendorStatusUpdated::rejected());
        }

        return $vendorProfile;
    }
}
