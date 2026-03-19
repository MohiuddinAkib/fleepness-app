<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\User;
use App\Data\ProductData;
use App\Models\Livestream;
use Illuminate\Support\Str;
use App\Data\LivestreamData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Spatie\LaravelData\DataCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use App\Data\Dto\GenerateSubscriberTokenData;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use App\Actions\Me\ListLikedLivestreamsAction;
use App\Actions\Me\ListSavedLivestreamsAction;
use App\Facades\Livestream as LivestreamFacade;
use Knuckles\Scribe\Attributes\Unauthenticated;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group('Content', 'Browse livestreams and interact with products, comments, likes, saves, and subscriber tokens.')]
class LivestreamController extends Controller
{
    #[Endpoint('List livestreams')]
    #[Response('{"data":[{"id":1,"title":"Friday Live Sale"}],"meta":{"current_page":1}}', 200)]
    #[Unauthenticated]
    public function index(): JsonResponse|Responsable
    {
        $livestreams = Livestream::query()
            ->with(['media', 'vendorProfile'])
            ->latest()
            ->paginate();

        return LivestreamData::collect($livestreams, PaginatedDataCollection::class);
    }

    #[Endpoint('Get livestream')]
    #[Response('{"data":{"id":1,"title":"Friday Live Sale","products":[]}}', 200)]
    #[Unauthenticated]
    public function show(Livestream $livestream): JsonResponse|Responsable
    {
        $livestream->load(['media', 'vendorProfile', 'products']);

        return LivestreamData::fromModel($livestream);
    }

    #[Authenticated]
    #[Endpoint('Like livestream')]
    #[Response('{"message":"Liked."}', 200)]
    public function like(Livestream $livestream, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $existingLike = $livestream->likes()->where('user_id', $user->getKey())->first();

        if (null === $existingLike) {
            $livestream->likes()->create(['user_id' => $user->getKey()]);
            $message = 'Liked.';
        } else {
            $existingLike->delete();
            $message = 'Unliked.';
        }

        return response()->json(['message' => $message]);
    }

    #[Authenticated]
    #[Endpoint('Save livestream')]
    #[Response('{"message":"Saved."}', 200)]
    public function save(Livestream $livestream, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $livestream->saves()->firstOrCreate(['user_id' => $user->getKey()]);

        return response()->json(['message' => 'Saved.']);
    }

    #[Endpoint('List livestream products')]
    #[Response('{"data":[{"id":15,"name":"Blue T-Shirt"}]}', 200)]
    #[Unauthenticated]
    public function products(Livestream $livestream): JsonResponse|Responsable
    {
        $products = $livestream->products()->with(['media', 'vendorProfile', 'category'])->get();

        return ProductData::collect($products, DataCollection::class);
    }

    #[Authenticated]
    #[Endpoint('List liked livestreams', 'Legacy compatibility alias for `/api/lives/liked`. Prefer `/api/me/livestreams/liked` in new consumers.')]
    #[Response('{"data":[{"id":1,"title":"Friday Live Sale"}]}', 200)]
    /**
     * Legacy alias for the historical `/api/lives/liked` route.
     *
     * Preferred modern path:
     * - use `/api/me/livestreams/liked` for the authenticated liked collection
     * - keep `/api/livestreams` as the canonical public content collection
     *
     * Retained only for backward compatibility with the mobile client.
     */
    public function liked(
        #[CurrentUser] User $user,
        ListLikedLivestreamsAction $listLikedLivestreams,
    ): JsonResponse|Responsable {
        $livestreams = $listLikedLivestreams->execute($user);

        return LivestreamData::collect($livestreams, PaginatedDataCollection::class);
    }

    #[Authenticated]
    #[Endpoint('List saved livestreams', 'Legacy compatibility alias for `/api/lives/saved`. Prefer `/api/me/livestreams/saved` in new clients.')]
    #[Response('{"data":[{"id":1,"title":"Friday Live Sale"}]}', 200)]
    /**
     * Legacy alias for the historical `/api/lives/saved` route.
     *
     * Preferred modern path:
     * - use `/api/me/livestreams/saved` for the authenticated saved collection
     * - keep `/api/livestreams` as the canonical public content collection
     */
    public function saved(
        #[CurrentUser] User $user,
        ListSavedLivestreamsAction $listSavedLivestreams,
    ): JsonResponse|Responsable {
        $livestreams = $listSavedLivestreams->execute($user);

        return LivestreamData::collect($livestreams, PaginatedDataCollection::class);
    }

    #[Authenticated]
    #[Endpoint('Get livestream likes count', 'Legacy compatibility alias for `/api/lives/{livestream}/likes-count`. Prefer the main livestream resource payload and real-time events for new clients.')]
    #[Response('{"likes_count":1}', 200)]
    /**
     * Legacy alias for older clients polling `/api/lives/{livestream}/likes-count`.
     *
     * Preferred modern path:
     * - consume `/api/livestreams/{livestream}` for the canonical resource
     * - subscribe to real-time broadcast updates for like counters where possible
     */
    public function likesCount(Livestream $livestream): JsonResponse|Responsable
    {
        return response()->json([
            'likes_count' => $livestream->likes()->count(),
        ]);
    }

    #[Endpoint('Generate livestream subscriber token', 'Returns a LiveKit subscriber token for an authenticated user or a guest viewer.')]
    #[Response('{"token":"eyJhbGciOi..."}', 200)]
    #[Unauthenticated]
    public function subscriberToken(
        Livestream $livestream,
        #[CurrentUser] ?User $user,
    ): JsonResponse|Responsable {
        abort_if($livestream->status->isFinished(), HttpResponse::HTTP_NOT_FOUND);
        abort_unless($livestream->status->isStarted(), HttpResponse::HTTP_UNPROCESSABLE_ENTITY);

        $identity = null !== $user?->getKey() ? (string) $user->getKey() : Str::random(8);
        $displayName = $user?->name ?? 'Guest';

        $data = new GenerateSubscriberTokenData(
            roomName: $livestream->room_name,
            identity: $identity,
            displayName: $displayName,
            isPublic: null === $user,
            metadata: ['livestream_identity' => $livestream->getKey()],
        );

        $token = LivestreamFacade::generateSubscriberToken($data);

        return response()->json(['token' => $token]);
    }
}
