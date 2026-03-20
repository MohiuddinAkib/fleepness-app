<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\User;
use App\Data\ProductData;
use App\Models\ShortVideo;
use App\Data\ShortVideoData;
use App\Models\ShortVideoLike;
use App\Models\ShortVideoSave;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Spatie\LaravelData\DataCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Unauthenticated;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;
use App\Data\Response\Content\ShortVideoLikeResponseData;
use App\Data\Response\Content\ShortVideoSaveResponseData;

#[Group('Content', 'Browse short videos and interact with comments, likes, and saves.')]
class ShortVideoController extends Controller
{
    #[Endpoint('List short videos')]
    #[Response('{"data":[{"id":1,"title":"New Collection Drop"}],"meta":{"current_page":1}}', 200)]
    #[Unauthenticated]
    /** @return PaginatedDataCollection<ShortVideoData> */
    public function index(): JsonResponse|Responsable
    {
        $videos = ShortVideo::query()
            ->with(['media', 'vendorProfile'])
            ->latest()
            ->paginate();

        return ShortVideoData::collect($videos, PaginatedDataCollection::class);
    }

    #[Endpoint('Get short video')]
    #[Response('{"data":{"id":1,"title":"New Collection Drop","products":[]}}', 200)]
    #[Unauthenticated]
    /** @return ShortVideoData */
    public function show(ShortVideo $shortVideo): JsonResponse|Responsable
    {
        $shortVideo->load(['media', 'vendorProfile', 'products']);

        return ShortVideoData::fromModel($shortVideo);
    }

    #[Authenticated]
    #[Endpoint('Like short video')]
    #[Response('{"message":"Liked."}', 200)]
    /** @return ShortVideoLikeResponseData */
    public function like(ShortVideo $shortVideo, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $like = $shortVideo->likes()
            ->where('user_id', $user->getKey())
            ->first();

        if ($like instanceof ShortVideoLike) {
            $like->delete();
            $shortVideo->update([
                'likes_count' => max(0, $shortVideo->likes_count - 1),
            ]);

            return response()->json(ShortVideoLikeResponseData::from([
                'message' => 'Like removed.',
                'liked' => false,
                'like_count' => $shortVideo->fresh()->likes_count,
            ])->toArray());
        }

        $shortVideo->likes()->create(['user_id' => $user->getKey()]);
        $shortVideo->increment('likes_count');

        return response()->json(ShortVideoLikeResponseData::from([
            'message' => 'Short liked.',
            'liked' => true,
            'like_count' => $shortVideo->fresh()->likes_count,
        ])->toArray());
    }

    #[Authenticated]
    #[Endpoint('Save short video')]
    #[Response('{"message":"Saved."}', 200)]
    /** @return ShortVideoSaveResponseData */
    public function save(ShortVideo $shortVideo, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $save = $shortVideo->saves()
            ->where('user_id', $user->getKey())
            ->first();

        if ($save instanceof ShortVideoSave) {
            $save->delete();

            return response()->json(ShortVideoSaveResponseData::from([
                'message' => 'Save removed.',
                'saved' => false,
                'save_count' => $shortVideo->saves()->count(),
            ])->toArray());
        }

        $shortVideo->saves()->create(['user_id' => $user->getKey()]);

        return response()->json(ShortVideoSaveResponseData::from([
            'message' => 'Short saved.',
            'saved' => true,
            'save_count' => $shortVideo->saves()->count(),
        ])->toArray());
    }

    #[Endpoint('List short video products')]
    #[Response('{"data":[{"id":15,"name":"Blue T-Shirt"}]}', 200)]
    #[Unauthenticated]
    /** @return DataCollection<ProductData> */
    public function products(ShortVideo $shortVideo): JsonResponse|Responsable
    {
        $products = $shortVideo->products()->with(['media', 'vendorProfile', 'category'])->get();

        return ProductData::collect($products, DataCollection::class);
    }
}
