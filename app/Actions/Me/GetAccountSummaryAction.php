<?php

declare(strict_types=1);

namespace App\Actions\Me;

use App\Models\User;
use App\Data\Me\AccountSummaryData;

class GetAccountSummaryAction
{
    public function execute(User $user): AccountSummaryData
    {
        $role = match (true) {
            null !== $user->vendorProfile => 'vendor',
            $user->hasRole('admin') => 'admin',
            default => $user->getRoleNames()->first() ?? 'user',
        };

        return new AccountSummaryData(
            userId: $user->getKey(),
            name: $user->name,
            role: $role,
            status: $user->vendorProfile?->status?->value,
        );
    }
}
