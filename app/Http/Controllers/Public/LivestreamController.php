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
use App\Data\Public\ListLivestreamsData;
use App\Data\Response\TokenResponseData;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use App\Data\Response\MessageResponseData;
use App\Data\Dto\GenerateSubscriberTokenData;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
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
    /** @return PaginatedDataCollection<LivestreamData> */
    public function index(ListLivestreamsData $data): JsonResponse|Responsable
    {
        $livestreams = Livestream::query()
            ->when(
                null !== $data->vendorId,
                fn ($query) => $query->where('vendor_profile_id', $data->vendorId)
            )
            ->with(['media', 'vendorProfile'])
            ->latest()
            ->paginate(perPage: $data->perPage, page: $data->page);

        return LivestreamData::collect($livestreams, PaginatedDataCollection::class);
    }

    #[Endpoint('Get livestream')]
    #[Response('{"data":{"id":1,"title":"Friday Live Sale","products":[]}}', 200)]
    #[Unauthenticated]
    /** @return LivestreamData */
    public function show(Livestream $livestream): JsonResponse|Responsable
    {
        $livestream->load(['media', 'vendorProfile', 'products']);

        return LivestreamData::fromModel($livestream);
    }

    #[Authenticated]
    #[Endpoint('Like livestream')]
    #[Response('{"message":"Liked."}', 200)]
    /** @return MessageResponseData */
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

        return response()->json(MessageResponseData::from([
            'message' => $message,
        ])->toArray());
    }

    #[Authenticated]
    #[Endpoint('Save livestream')]
    #[Response('{"message":"Saved."}', 200)]
    /** @return MessageResponseData */
    public function save(Livestream $livestream, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $livestream->saves()->firstOrCreate(['user_id' => $user->getKey()]);

        return response()->json(MessageResponseData::from([
            'message' => 'Saved.',
        ])->toArray());
    }

    #[Endpoint('List livestream products')]
    #[Response('{"data":[{"id":15,"name":"Blue T-Shirt"}]}', 200)]
    #[Unauthenticated]
    /** @return DataCollection<ProductData> */
    public function products(Livestream $livestream): JsonResponse|Responsable
    {
        $products = $livestream->products()->with(['media', 'vendorProfile', 'category'])->get();

        return ProductData::collect($products, DataCollection::class);
    }

    #[Endpoint('Generate livestream subscriber token', 'Returns a LiveKit subscriber token for an authenticated user or a guest viewer.')]
    #[Response('{"token":"eyJhbGciOi..."}', 200)]
    #[Unauthenticated]
    /** @return TokenResponseData */
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

        return response()->json(TokenResponseData::from([
            'token' => $token,
        ])->toArray());
    }
}
