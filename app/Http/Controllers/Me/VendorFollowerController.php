<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Data\UserData;
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

#[Group('Vendor Profile', 'Manage vendor-specific authenticated resources.')]
class VendorFollowerController extends Controller
{
    #[Authenticated]
    #[Endpoint('List vendor followers')]
    #[Response('{"data":[{"id":1,"name":"Jane Doe"}]}', 200)]
    /** @return DataCollection<UserData> */
    public function index(
        #[CurrentUser] User $user,
        ListVendorFollowersAction $listVendorFollowers,
    ): JsonResponse|Responsable {
        $followers = $listVendorFollowers->execute($user);

        return UserData::collect($followers, DataCollection::class);
    }
}
