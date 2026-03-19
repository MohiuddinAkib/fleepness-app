<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Data\LivestreamData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use App\Actions\Me\ListSavedLivestreamsAction;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;

#[Group('Content', 'Manage the authenticated user\'s saved livestreams.')]
class SavedLivestreamController extends Controller
{
    #[Authenticated]
    #[Endpoint('List saved livestreams')]
    #[Response('{"data":[{"id":1,"title":"Friday Live Sale"}],"meta":{"current_page":1}}', 200)]
    public function index(
        #[CurrentUser] User $user,
        ListSavedLivestreamsAction $listSavedLivestreams,
    ): JsonResponse|Responsable {
        $livestreams = $listSavedLivestreams->execute($user);

        return LivestreamData::collect($livestreams, PaginatedDataCollection::class);
    }
}
