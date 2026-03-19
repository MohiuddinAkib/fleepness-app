<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Enums\VendorStatus;
use App\Models\VendorProfile;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MarketplaceHealthWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $approvedVendors = VendorProfile::query()
            ->where('status', VendorStatus::Approved)
            ->count();
        $pendingVendors = VendorProfile::query()
            ->where('status', VendorStatus::Pending)
            ->count();
        $approvedProducts = Product::query()
            ->where('is_approved', true)
            ->count();
        $pendingProducts = Product::query()
            ->where('is_approved', false)
            ->count();

        return [
            Stat::make('Approved Vendors', number_format($approvedVendors))
                ->description('Live vendors cleared by admin')
                ->icon('heroicon-o-building-storefront')
                ->color('success'),
            Stat::make('Pending Vendor Reviews', number_format($pendingVendors))
                ->description('Applications waiting for approval')
                ->icon('heroicon-o-user-plus')
                ->color('warning'),
            Stat::make('Approved Products', number_format($approvedProducts))
                ->description('Catalog items visible after review')
                ->icon('heroicon-o-shopping-bag')
                ->color('primary'),
            Stat::make('Pending Product Reviews', number_format($pendingProducts))
                ->description('Products still awaiting approval')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('warning'),
        ];
    }
}
