<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VendorOrder;
use App\Enums\VendorOrderStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Date;
use Illuminate\Container\Attributes\CurrentUser;

/**
 * Legacy compatibility endpoints retained for the current React Native client.
 *
 * Preferred modern replacements:
 * - `/api/seller/status` -> `/api/vendor-application/status` when checking application state
 *   or `/api/me/vendor` when the client needs the full vendor profile payload.
 * - `/api/me/role` -> `/api/me` plus `/api/me/vendor` when the client needs richer user/vendor context.
 * - `/api/user/balance-stats` -> `/api/me/balance` for the current vendor balance endpoint.
 *
 * These aliases intentionally preserve historical response shapes. New clients and AI agents should
 * migrate to the modern endpoints above instead of expanding this controller further.
 */
class CompatibilityController extends Controller
{
    /**
     * Legacy alias for older clients expecting `/api/seller/status`.
     *
     * Prefer `/api/vendor-application/status` for application workflow checks or `/api/me/vendor`
     * for the richer vendor profile payload used by the current API design.
     */
    public function sellerStatus(#[CurrentUser] User $user): JsonResponse
    {
        return response()->json([
            'status' => $user->vendorProfile?->status?->value,
        ]);
    }

    /**
     * Legacy alias for older clients expecting `/api/me/role`.
     *
     * Prefer `/api/me` for the authenticated user payload and `/api/me/vendor` when vendor-specific
     * state is needed. This legacy response collapses multiple concepts into one small payload.
     */
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

    /**
     * Legacy alias for older clients expecting `/api/user/balance-stats`.
     *
     * Prefer `/api/me/balance`, which is the canonical vendor balance endpoint in the refactored API.
     * This compatibility method keeps the older aggregate shape alive while the mobile client migrates.
     */
    public function balanceStats(#[CurrentUser] User $user): JsonResponse
    {
        $vendorProfile = $user->vendorProfile;
        $deliveredOrders = null === $vendorProfile
            ? collect()
            : VendorOrder::query()
                ->whereBelongsTo($vendorProfile, 'vendorProfile')
                ->where('status', VendorOrderStatus::Delivered)
                ->get();

        $today = Date::today();
        $startOfWeek = Date::now()->startOfWeek();
        $startOfMonth = Date::now()->startOfMonth();

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
