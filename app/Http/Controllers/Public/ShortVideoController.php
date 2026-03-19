<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\User;
use App\Data\CommentData;
use App\Data\ProductData;
use App\Models\ShortVideo;
use App\Data\ShortVideoData;
use App\Models\ShortVideoLike;
use App\Models\ShortVideoSave;
use App\Models\ShortVideoComment;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Spatie\LaravelData\DataCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use App\Data\ShortVideo\StoreCommentData;
use Knuckles\Scribe\Attributes\BodyParam;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Unauthenticated;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group('Content', 'Browse short videos and interact with comments, likes, and saves.')]
class ShortVideoController extends Controller
{
    #[Endpoint('List short videos')]
    #[Response('{"data":[{"id":1,"title":"New Collection Drop"}],"meta":{"current_page":1}}', 200)]
    #[Unauthenticated]
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
    public function show(ShortVideo $shortVideo): JsonResponse|Responsable
    {
        $shortVideo->load(['media', 'vendorProfile', 'products']);

        return ShortVideoData::fromModel($shortVideo);
    }

    #[Endpoint('List short video comments')]
    #[Response('{"data":[{"id":1,"comment":"Great video!"}],"meta":{"current_page":1}}', 200)]
    #[Unauthenticated]
    public function comments(ShortVideo $shortVideo): JsonResponse|Responsable
    {
        $comments = $shortVideo->comments()
            ->with('user')
            ->latest()
            ->paginate();

        return CommentData::collect(
            $comments->through(fn (ShortVideoComment $c) => CommentData::fromShortVideoComment($c)),
            PaginatedDataCollection::class
        );
    }

    #[Authenticated]
    #[BodyParam('comment', 'string', required: true, example: 'Great video!')]
    #[Endpoint('Post short video comment')]
    #[Response('{"data":{"id":1,"comment":"Great video!"}}', 201)]
    public function storeComment(
        StoreCommentData $data,
        ShortVideo $shortVideo,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $comment = $shortVideo->comments()->create([
            'user_id' => $user->getKey(),
            'comment' => $data->comment,
        ]);

        $comment->load('user');

        return CommentData::fromShortVideoComment($comment);
    }

    #[Authenticated]
    #[Endpoint('Delete short video comment')]
    #[Response('{"message":"Comment deleted."}', 200)]
    public function destroyComment(
        ShortVideo $shortVideo,
        ShortVideoComment $comment,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        abort_unless($comment->shortVideo()->is($shortVideo), HttpResponse::HTTP_NOT_FOUND);
        abort_unless($comment->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $comment->delete();

        return response()->json(['message' => 'Comment deleted.']);
    }

    #[Authenticated]
    #[Endpoint('Like short video')]
    #[Response('{"message":"Liked."}', 200)]
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

            return response()->json([
                'message' => 'Like removed.',
                'liked' => false,
                'like_count' => $shortVideo->fresh()->likes_count,
            ]);
        }

        $shortVideo->likes()->create(['user_id' => $user->getKey()]);
        $shortVideo->increment('likes_count');

        return response()->json([
            'message' => 'Short liked.',
            'liked' => true,
            'like_count' => $shortVideo->fresh()->likes_count,
        ]);
    }

    #[Authenticated]
    #[Endpoint('Save short video')]
    #[Response('{"message":"Saved."}', 200)]
    public function save(ShortVideo $shortVideo, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $save = $shortVideo->saves()
            ->where('user_id', $user->getKey())
            ->first();

        if ($save instanceof ShortVideoSave) {
            $save->delete();

            return response()->json([
                'message' => 'Save removed.',
                'saved' => false,
                'save_count' => $shortVideo->saves()->count(),
            ]);
        }

        $shortVideo->saves()->create(['user_id' => $user->getKey()]);

        return response()->json([
            'message' => 'Short saved.',
            'saved' => true,
            'save_count' => $shortVideo->saves()->count(),
        ]);
    }

    #[Authenticated]
    #[Endpoint('List saved short videos', 'Legacy compatibility alias for `/api/shorts/saved`. Prefer the canonical `/api/short-videos` collection plus saved-state handling in new clients until a dedicated modern me-scoped collection exists.')]
    #[Response('{"data":[{"id":1,"title":"New Collection Drop"}]}', 200)]
    /**
     * Legacy alias for the historical `/api/shorts/saved` route.
     *
     * Preferred modern path:
     * - use `/api/short-videos` as the canonical collection
     * - migrate saved-content views to a future me-scoped short-video endpoint instead of extending the `/api/shorts/*` alias surface
     *
     * Kept only so the current React Native client continues to function during migration.
     */
    public function saved(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $videos = ShortVideo::query()
            ->whereHas('saves', fn ($query) => $query->where('user_id', $user->getKey()))
            ->with(['media', 'vendorProfile'])
            ->latest()
            ->paginate();

        return ShortVideoData::collect($videos, PaginatedDataCollection::class);
    }

    #[Endpoint('List short video products')]
    #[Response('{"data":[{"id":15,"name":"Blue T-Shirt"}]}', 200)]
    #[Unauthenticated]
    public function products(ShortVideo $shortVideo): JsonResponse|Responsable
    {
        $products = $shortVideo->products()->with(['media', 'vendorProfile', 'category'])->get();

        return ProductData::collect($products, DataCollection::class);
    }
}
