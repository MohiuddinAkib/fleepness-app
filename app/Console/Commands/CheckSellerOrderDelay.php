<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\VendorOrder;
use Illuminate\Console\Command;

class CheckSellerOrderDelay extends Command
{
    protected $signature = 'orders:check-delay';

    protected $description = 'Mark vendor orders as delayed if expected delivery time has passed';

    public function handle(): int
    {
        $count = VendorOrder::delayed()->update(['is_delayed' => true]);

        $this->info("{$count} vendor orders marked as delayed.");

        return self::SUCCESS;
    }
}
