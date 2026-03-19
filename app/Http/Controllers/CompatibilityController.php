<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VendorOrder;
use Carbon\CarbonImmutable;
use App\Enums\VendorOrderStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Container\Attributes\CurrentUser;

class CompatibilityController extends Controller
{
    public function sellerStatus(#[CurrentUser] User $user): JsonResponse
    {
        return response()->json([
            'status' => $user->vendorProfile?->status?->value,
        ]);
    }

    public function role(#[CurrentUser] User $user): JsonResponse
    {
        $role = null;

        if (null !== $user->vendorProfile) {
            $role = 'vendor';
        } elseif ($user->hasRole('admin')) {
            $role = 'admin';
        } else {
            $role = $user->getRoleNames()->first() ?? 'user';
        }

        return response()->json([
            'user_id' => $user->getKey(),
            'name' => $user->name,
            'role' => $role,
            'status' => $user->vendorProfile?->status?->value,
        ]);
    }

    public function balanceStats(#[CurrentUser] User $user): JsonResponse
    {
        $vendorProfile = $user->vendorProfile;
        $deliveredOrders = null === $vendorProfile
            ? collect()
            : VendorOrder::query()
                ->whereBelongsTo($vendorProfile, 'vendorProfile')
                ->where('status', VendorOrderStatus::Delivered)
                ->get();

        $today = CarbonImmutable::today();
        $startOfWeek = CarbonImmutable::now()->startOfWeek();
        $startOfMonth = CarbonImmutable::now()->startOfMonth();

        return response()->json([
            'user_id' => $user->getKey(),
            'name' => $user->name,
            'daily_balance' => number_format((float) $deliveredOrders->where('created_at', '>=', $today)->sum('balance'), 2, '.', ''),
            'weekly_balance' => number_format((float) $deliveredOrders->where('created_at', '>=', $startOfWeek)->sum('balance'), 2, '.', ''),
            'monthly_balance' => number_format((float) $deliveredOrders->where('created_at', '>=', $startOfMonth)->sum('balance'), 2, '.', ''),
            'lifetime_balance' => number_format((float) ($vendorProfile?->balance ?? 0), 2, '.', ''),
        ]);
    }
}
