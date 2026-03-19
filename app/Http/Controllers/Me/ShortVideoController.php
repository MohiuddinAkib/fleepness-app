<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\ShortVideo;
use App\Data\ShortVideoData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Data\ShortVideo\StoreShortVideoData;
use Illuminate\Contracts\Support\Responsable;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ShortVideoController extends Controller
{
    public function index(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $videos = ShortVideo::query()
            ->where('vendor_profile_id', $user->vendorProfile?->getKey())
            ->with(['media', 'vendorProfile'])
            ->latest()
            ->paginate();

        return ShortVideoData::collect($videos, PaginatedDataCollection::class);
    }

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
