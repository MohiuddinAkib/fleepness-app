<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\ShortVideo;
use App\Data\ShortVideoData;
use App\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Data\ShortVideo\StoreShortVideoData;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ShortVideoController extends Controller
{
    public function index(#[CurrentUser] User $user): JsonResponse
    {
        $videos = ShortVideo::query()
            ->where('vendor_profile_id', $user->vendorProfile?->getKey())
            ->with(['media', 'vendorProfile'])
            ->latest()
            ->paginate();

        return Response::json(ShortVideoData::collect($videos));
    }

    public function store(
        StoreShortVideoData $data,
        #[CurrentUser] User $user,
    ): JsonResponse {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);

        $video = ShortVideo::query()->create([
            'vendor_profile_id' => $vendorProfile->getKey(),
            'title' => $data->title,
            'description' => $data->description,
        ]);

        $video->load(['media', 'vendorProfile']);

        return Response::json(
            ['data' => ShortVideoData::fromModel($video)],
            HttpResponse::HTTP_CREATED
        );
    }

    public function update(
        StoreShortVideoData $data,
        ShortVideo $shortVideo,
        #[CurrentUser] User $user,
    ): JsonResponse {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($shortVideo->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $shortVideo->update([
            'title' => $data->title,
            'description' => $data->description,
        ]);

        $shortVideo->load(['media', 'vendorProfile']);

        return Response::json(['data' => ShortVideoData::fromModel($shortVideo)]);
    }

    public function destroy(
        ShortVideo $shortVideo,
        #[CurrentUser] User $user,
    ): JsonResponse {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($shortVideo->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $shortVideo->delete();

        return Response::json(['message' => 'Short video deleted.']);
    }
}
