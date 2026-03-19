<?php

declare(strict_types=1);

namespace App\Http\Controllers\Legacy;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use App\Actions\Me\GetAccountSummaryAction;
use Knuckles\Scribe\Attributes\Authenticated;
use App\Actions\Me\GetVendorBalanceDataAction;
use Illuminate\Container\Attributes\CurrentUser;

/**
 * Legacy compatibility endpoints retained for the current React Native client.
 *
 * Preferred modern replacements:
 * - `/api/seller/status` -> `/api/v1/me/summaries`, `/api/v1/vendor-applications/status`, or `/api/v1/me/vendors`
 *   depending on whether the client needs a compact summary, application workflow state, or the full vendor payload.
 * - `/api/me/role` -> `/api/v1/me/summaries` plus `/api/v1/me/vendors` when the client needs richer user/vendor context.
 * - `/api/user/balance-stats` -> `/api/v1/me/balances` for the current vendor balance endpoint.
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
     * Prefer `/api/v1/me/summaries` for a compact authenticated-user summary, `/api/v1/vendor-applications/status`
     * for application workflow checks, or `/api/v1/me/vendors` for the richer vendor payload.
     */
    #[Authenticated]
    #[Endpoint('Get seller status', 'Legacy compatibility alias for `/api/seller/status`. Prefer `/api/v1/me/summaries` for a compact summary, `/api/v1/vendor-applications/status` for application workflow checks, or `/api/v1/me/vendors` for the richer vendor payload.')]
    #[Response('{"status":"approved"}', 200)]
    public function sellerStatus(
        #[CurrentUser] User $user,
        GetAccountSummaryAction $getAccountSummary,
    ): JsonResponse {
        $summary = $getAccountSummary->execute($user);

        return response()->json([
            'status' => $summary->status,
        ]);
    }

    /**
     * Legacy alias for older clients expecting `/api/me/role`.
     *
     * Prefer `/api/v1/me/summaries` for the canonical compact summary and `/api/v1/me/vendors` when vendor-specific
     * state is needed. This legacy response collapses multiple concepts into one small payload.
     */
    #[Authenticated]
    #[Endpoint('Get user role summary', 'Legacy compatibility alias for `/api/me/role`. Prefer `/api/v1/me/summaries` and `/api/v1/me/vendors` for the canonical authenticated-user and vendor payloads.')]
    #[Response('{"user_id":1,"name":"Vendor User","role":"vendor","status":"approved"}', 200)]
    public function role(
        #[CurrentUser] User $user,
        GetAccountSummaryAction $getAccountSummary,
    ): JsonResponse {
        $summary = $getAccountSummary->execute($user);

        return response()->json([
            'user_id' => $summary->userId,
            'name' => $summary->name,
            'role' => $summary->role,
            'status' => $summary->status,
        ]);
    }

    /**
     * Legacy alias for older clients expecting `/api/user/balance-stats`.
     *
     * Prefer `/api/v1/me/balances`, which is the canonical vendor balance endpoint in the refactored API.
     * This compatibility method keeps the older aggregate shape alive while the mobile client migrates.
     */
    #[Authenticated]
    #[Endpoint('Get vendor balance stats', 'Legacy compatibility alias for `/api/user/balance-stats`. Prefer `/api/v1/me/balances`, which now includes both current balance fields and delivered-order aggregates.')]
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
