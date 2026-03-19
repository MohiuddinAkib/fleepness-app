<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\VendorOrder;
use Illuminate\Console\Command;
use App\Enums\VendorOrderStatus;

class CheckSellerOrderDelay extends Command
{
    protected $signature = 'orders:check-delay';

    protected $description = 'Mark vendor orders as delayed if expected delivery time has passed';

    public function handle(): int
    {
        $count = VendorOrder::query()
            ->whereNotIn('status', [VendorOrderStatus::Delivered, VendorOrderStatus::Rejected])
            ->whereNotNull('expected_delivery_at')
            ->where('expected_delivery_at', '<', now())
            ->where('is_delayed', false)
            ->update(['is_delayed' => true]);

        $this->info("{$count} vendor orders marked as delayed.");

        return self::SUCCESS;
    }
}
