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
use App\Actions\Me\ListVendorFollowersAction;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Illuminate\Container\Attributes\CurrentUser;

#[Group('Vendors')]
class FollowController extends Controller
{
    #[Authenticated]
    #[Endpoint('List followed vendors', 'Returns a paginated list of vendor profiles that the authenticated user follows.')]
    #[Response('{"data": [{"id": 1, "shop_name": "Flash Store"}]}', 200)]
    public function followings(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $vendorProfiles = $user->following()->get();

        return VendorProfileData::collect($vendorProfiles, DataCollection::class);
    }

    #[Authenticated]
    #[Endpoint('List vendor followers', 'Legacy compatibility alias for `/api/followers`. Prefer `/api/me/vendors/followers` for the canonical authenticated vendor follower collection.')]
    #[Response('{"data":[{"id":1,"name":"Jane Doe"}]}', 200)]
    /**
     * Legacy alias for `/api/followers`.
     *
     * Preferred modern direction:
     * - use `/api/me/vendors/followers` for the canonical authenticated vendor follower collection
     * - keep `/api/vendors/{vendorProfile}/follow` as the canonical follow/unfollow action surface
     *
     * The current mobile client still consumes this historical top-level route, so it stays available.
     */
    public function followers(
        #[CurrentUser] User $user,
        ListVendorFollowersAction $listVendorFollowers,
    ): JsonResponse|Responsable {
        $users = $listVendorFollowers->execute($user);

        return response()->json([
            'followers' => UserData::collect($users, DataCollection::class)->toArray(),
            'data' => UserData::collect($users, DataCollection::class)->toArray(),
        ]);
    }
}
