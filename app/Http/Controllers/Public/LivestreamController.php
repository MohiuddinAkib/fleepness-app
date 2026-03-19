<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\User;
use App\Data\CommentData;
use App\Data\ProductData;
use App\Models\Livestream;
use Illuminate\Support\Str;
use App\Data\LivestreamData;
use App\Models\LivestreamComment;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\LaravelData\DataCollection;
use App\Data\Livestream\StoreCommentData;
use App\Data\Dto\GenerateSubscriberTokenData;
use Illuminate\Contracts\Support\Responsable;
use App\Facades\Livestream as LivestreamFacade;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class LivestreamController extends Controller
{
    public function index(): JsonResponse|Responsable
    {
        $livestreams = Livestream::query()
            ->with(['media', 'vendorProfile'])
            ->latest()
            ->paginate();

        return LivestreamData::collect($livestreams, PaginatedDataCollection::class);
    }

    public function show(Livestream $livestream): JsonResponse|Responsable
    {
        $livestream->load(['media', 'vendorProfile', 'products']);

        return LivestreamData::fromModel($livestream);
    }

    public function comments(Livestream $livestream): JsonResponse|Responsable
    {
        $comments = $livestream->comments()
            ->with('user')
            ->latest()
            ->paginate();

        return CommentData::collect(
            $comments->through(fn (LivestreamComment $c) => CommentData::fromLivestreamComment($c)),
            PaginatedDataCollection::class
        );
    }

    public function storeComment(
        StoreCommentData $data,
        Livestream $livestream,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $comment = $livestream->comments()->create([
            'user_id' => $user->getKey(),
            'comment' => $data->comment,
        ]);

        $comment->load('user');

        return CommentData::fromLivestreamComment($comment);
    }

    public function destroyComment(
        Livestream $livestream,
        LivestreamComment $comment,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        abort_unless($comment->livestream()->is($livestream), HttpResponse::HTTP_NOT_FOUND);
        abort_unless($comment->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $comment->delete();

        return response()->json(['message' => 'Comment deleted.']);
    }

    public function like(Livestream $livestream, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $livestream->likes()->firstOrCreate(['user_id' => $user->getKey()]);

        return response()->json(['message' => 'Liked.']);
    }

    public function save(Livestream $livestream, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $livestream->saves()->firstOrCreate(['user_id' => $user->getKey()]);

        return response()->json(['message' => 'Saved.']);
    }

    public function products(Livestream $livestream): JsonResponse|Responsable
    {
        $products = $livestream->products()->with(['media', 'vendorProfile', 'category'])->get();

        return ProductData::collect($products, DataCollection::class);
    }

    public function subscriberToken(
        Livestream $livestream,
        #[CurrentUser] ?User $user,
    ): JsonResponse|Responsable {
        abort_if($livestream->status->isFinished(), HttpResponse::HTTP_NOT_FOUND);
        abort_unless($livestream->status->isStarted(), HttpResponse::HTTP_UNPROCESSABLE_ENTITY);

        $identity = null !== $user?->getKey() ? (string) $user->getKey() : Str::random(8);
        $displayName = $user?->name ?? 'Guest';

        $data = new GenerateSubscriberTokenData(
            roomName: $livestream->getRoomName(),
            identity: $identity,
            displayName: $displayName,
            isPublic: null === $user,
            metadata: ['livestream_identity' => $livestream->getKey()],
        );

        $token = LivestreamFacade::generateSubscriberToken($data);

        return response()->json(['token' => $token]);
    }
}
