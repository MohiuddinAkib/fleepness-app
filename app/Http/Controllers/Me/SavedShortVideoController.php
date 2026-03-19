<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Data\ShortVideoData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use App\Actions\Me\ListSavedShortVideosAction;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;

#[Group('Content', 'Manage the authenticated user\'s saved short videos.')]
class SavedShortVideoController extends Controller
{
    #[Authenticated]
    #[Endpoint('List saved short videos')]
    #[Response('{"data":[{"id":1,"title":"New Collection Drop"}],"meta":{"current_page":1}}', 200)]
    public function index(
        #[CurrentUser] User $user,
        ListSavedShortVideosAction $listSavedShortVideos,
    ): JsonResponse|Responsable {
        $videos = $listSavedShortVideos->execute($user);

        return ShortVideoData::collect($videos, PaginatedDataCollection::class);
    }
}
