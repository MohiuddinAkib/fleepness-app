<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
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
    public function followings(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $vendorProfiles = $user->following()->get();

        return VendorProfileData::collect($vendorProfiles, DataCollection::class);
    }
}
