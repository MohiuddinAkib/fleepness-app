<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\VendorOrder;
use App\Enums\VendorOrderStatus;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CommerceOverviewWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalOrders = Order::query()->count();
        $deliveredVendorOrders = VendorOrder::query()
            ->where('status', VendorOrderStatus::Delivered)
            ->count();
        $pendingVendorOrders = VendorOrder::query()
            ->where('status', VendorOrderStatus::Pending)
            ->count();
        $grossRevenue = (float) Order::query()->sum('grand_total');

        return [
            Stat::make('Total Orders', number_format($totalOrders))
                ->description('All customer orders placed')
                ->icon('heroicon-o-shopping-bag')
                ->color('primary'),
            Stat::make('Delivered Vendor Orders', number_format($deliveredVendorOrders))
                ->description('Vendor orders completed successfully')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Pending Vendor Orders', number_format($pendingVendorOrders))
                ->description('Orders still waiting on vendor action')
                ->icon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Gross Revenue', '$'.number_format($grossRevenue, 2))
                ->description('Sum of order grand totals')
                ->icon('heroicon-o-banknotes')
                ->color('success'),
        ];
    }
}
