<?php

declare(strict_types=1);

namespace App\Actions\Me;

use App\Models\User;
use App\Models\VendorOrder;
use App\Enums\VendorOrderStatus;
use App\Data\Me\VendorBalanceData;
use Illuminate\Support\Facades\Date;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class GetVendorBalanceDataAction
{
    public function execute(User $user): VendorBalanceData
    {
        $vendorProfile = $user->vendorProfile;

        abort_if(null === $vendorProfile, HttpResponse::HTTP_NOT_FOUND, 'No vendor profile found.');

        $deliveredOrders = VendorOrder::query()
            ->whereBelongsTo($vendorProfile, 'vendorProfile')
            ->where('status', VendorOrderStatus::Delivered)
            ->get();

        $today = Date::today();
        $startOfWeek = Date::now()->startOfWeek();
        $startOfMonth = Date::now()->startOfMonth();

        return new VendorBalanceData(
            balance: $this->formatAmount($vendorProfile->balance),
            totalSales: $this->formatAmount($vendorProfile->total_sales),
            withdrawnAmount: $this->formatAmount($vendorProfile->withdrawn_amount),
            dailyBalance: $this->formatAmount($deliveredOrders->where('created_at', '>=', $today)->sum('balance')),
            weeklyBalance: $this->formatAmount($deliveredOrders->where('created_at', '>=', $startOfWeek)->sum('balance')),
            monthlyBalance: $this->formatAmount($deliveredOrders->where('created_at', '>=', $startOfMonth)->sum('balance')),
            lifetimeBalance: $this->formatAmount($vendorProfile->balance),
        );
    }

    private function formatAmount(null|float|int|string $amount): string
    {
        return number_format((float) ($amount ?? 0), 2, '.', '');
    }
}
