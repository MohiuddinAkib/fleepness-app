<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Data\UserData;
use App\Data\VendorProfileData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Spatie\LaravelData\DataCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Illuminate\Container\Attributes\CurrentUser;

#[Group('Vendors')]
class FollowController extends Controller
{
    #[Authenticated]
    #[Endpoint('List followed vendors', 'Returns a paginated list of vendor profiles that the authenticated user follows.')]
    #[Response('{"data": [{"id": 1, "shop_name": "Flash Store"}]}', 200)]
    public function following(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $vendorProfiles = $user->following()->get();

        return VendorProfileData::collect($vendorProfiles, DataCollection::class);
    }

    #[Authenticated]
    #[Endpoint('List vendor followers', 'Legacy compatibility alias for `/api/followers`. Prefer vendor-centric profile endpoints or dedicated me-scoped follower endpoints in new clients.')]
    #[Response('{"data":[{"id":1,"name":"Jane Doe"}]}', 200)]
    /**
     * Legacy alias for `/api/followers`.
     *
     * Preferred modern direction:
     * - keep follow state grouped around `/api/vendors/{vendorProfile}/follow`
     * - expose any future follower-management screens under a me-scoped vendor namespace
     *
     * The current mobile client still consumes this historical top-level route, so it stays available.
     */
    public function followers(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $vendorProfile = $user->vendorProfile;
        $followers = $vendorProfile?->followers()->with('user')->get() ?? collect();
        $users = $followers
            ->pluck('user')
            ->filter()
            ->values();

        return response()->json([
            'followers' => UserData::collect($users, DataCollection::class)->toArray(),
            'data' => UserData::collect($users, DataCollection::class)->toArray(),
        ]);
    }
}
