<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\Authenticated;
use App\Actions\Me\GetVendorBalanceDataAction;
use Illuminate\Container\Attributes\CurrentUser;

/**
 * Legacy compatibility endpoints retained for the current React Native client.
 *
 * Preferred modern replacements:
 * - `/api/seller/status` -> `/api/vendor-applications/status` when checking application state
 *   or `/api/me/vendors` when the client needs the full vendor profile payload.
 * - `/api/me/role` -> `/api/me` plus `/api/me/vendors` when the client needs richer user/vendor context.
 * - `/api/user/balance-stats` -> `/api/me/balances` for the current vendor balance endpoint.
 *
 * These aliases intentionally preserve historical response shapes. New clients and AI agents should
 * migrate to the modern endpoints above instead of expanding this controller further.
 */
#[Group('Compatibility', 'Legacy compatibility aliases retained for the current mobile client. Prefer the replacement paths called out in each endpoint description.')]
class CompatibilityController extends Controller
{
    /**
     * Legacy alias for older clients expecting `/api/seller/status`.
     *
     * Prefer `/api/vendor-applications/status` for application workflow checks or `/api/me/vendors`
     * for the richer vendor profile payload used by the current API design.
     */
    #[Authenticated]
    #[Endpoint('Get seller status', 'Legacy compatibility alias for `/api/seller/status`. Prefer `/api/vendor-applications/status` for application workflow checks or `/api/me/vendors` for the richer vendor payload.')]
    #[Response('{"status":"approved"}', 200)]
    public function sellerStatus(#[CurrentUser] User $user): JsonResponse
    {
        return response()->json([
            'status' => $user->vendorProfile?->status?->value,
        ]);
    }

    /**
     * Legacy alias for older clients expecting `/api/me/role`.
     *
     * Prefer `/api/me` for the authenticated user payload and `/api/me/vendors` when vendor-specific
     * state is needed. This legacy response collapses multiple concepts into one small payload.
     */
    #[Authenticated]
    #[Endpoint('Get user role summary', 'Legacy compatibility alias for `/api/me/role`. Prefer `/api/me` and `/api/me/vendors` for the canonical authenticated-user and vendor payloads.')]
    #[Response('{"user_id":1,"name":"Vendor User","role":"vendor","status":"approved"}', 200)]
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
     * Prefer `/api/me/balances`, which is the canonical vendor balance endpoint in the refactored API.
     * This compatibility method keeps the older aggregate shape alive while the mobile client migrates.
     */
    #[Authenticated]
    #[Endpoint('Get vendor balance stats', 'Legacy compatibility alias for `/api/user/balance-stats`. Prefer `/api/me/balances`, which now includes both current balance fields and delivered-order aggregates.')]
    #[Response('{"user_id":1,"name":"Vendor User","daily_balance":"50.00","weekly_balance":"50.00","monthly_balance":"50.00","lifetime_balance":"250.00"}', 200)]
    public function balanceStats(
        #[CurrentUser] User $user,
        GetVendorBalanceDataAction $getVendorBalanceData,
    ): JsonResponse {
        $balance = $getVendorBalanceData->execute($user);

        return response()->json([
            'user_id' => $user->getKey(),
            'name' => $user->name,
            'daily_balance' => $balance->dailyBalance,
            'weekly_balance' => $balance->weeklyBalance,
            'monthly_balance' => $balance->monthlyBalance,
            'lifetime_balance' => $balance->lifetimeBalance,
        ]);
    }
}
