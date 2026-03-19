<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\ShortVideo;
use App\Data\ShortVideoData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\BodyParam;
use App\Data\ShortVideo\StoreShortVideoData;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group('Short Videos', 'Vendor short-video management. Short videos are TikTok-style shoppable content.')]
class ShortVideoController extends Controller
{
    #[Authenticated]
    #[Endpoint('List own short videos')]
    #[Response('{"data":[{"id":1,"title":"New Collection Drop"}],"meta":{"current_page":1}}', 200)]
    public function index(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $videos = ShortVideo::query()
            ->where('vendor_profile_id', $user->vendorProfile?->getKey())
            ->with(['media', 'vendorProfile'])
            ->latest()
            ->paginate();

        return ShortVideoData::collect($videos, PaginatedDataCollection::class);
    }

    #[Authenticated]
    #[BodyParam('title', 'string', required: true, example: 'Summer Collection')]
    #[BodyParam('description', 'string', required: false, nullable: true)]
    #[BodyParam('video', 'file', required: true, example: 'No-example')]
    #[BodyParam('thumbnail', 'file', required: false, nullable: true)]
    #[Endpoint('Upload short video')]
    #[Response('{"data":{"id":1,"title":"Summer Collection"}}', 201)]
    public function store(
        StoreShortVideoData $data,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);

        $video = ShortVideo::query()->create([
            'vendor_profile_id' => $vendorProfile->getKey(),
            'title' => $data->title,
            'description' => $data->description,
        ]);

        $video->load(['media', 'vendorProfile']);

        return ShortVideoData::fromModel($video);
    }

    #[Authenticated]
    #[BodyParam('title', 'string', required: false)]
    #[BodyParam('description', 'string', required: false, nullable: true)]
    #[Endpoint('Update short video')]
    #[Response('{"data":{"id":1,"title":"Updated Title"}}', 200)]
    public function update(
        StoreShortVideoData $data,
        ShortVideo $shortVideo,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($shortVideo->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $shortVideo->update([
            'title' => $data->title,
            'description' => $data->description,
        ]);

        $shortVideo->load(['media', 'vendorProfile']);

        return ShortVideoData::fromModel($shortVideo);
    }

    #[Authenticated]
    #[Endpoint('Delete short video')]
    #[Response('{"message":"Short video deleted."}', 200)]
    public function destroy(
        ShortVideo $shortVideo,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($shortVideo->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $shortVideo->delete();

        return response()->json(['message' => 'Short video deleted.']);
    }
}
