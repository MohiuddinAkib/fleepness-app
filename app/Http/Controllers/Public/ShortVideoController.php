<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\User;
use App\Data\CommentData;
use App\Models\ShortVideo;
use App\Data\ShortVideoData;
use App\Attributes\CurrentUser;
use App\Models\ShortVideoComment;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Data\ShortVideo\StoreCommentData;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ShortVideoController extends Controller
{
    public function index(): JsonResponse
    {
        $videos = ShortVideo::query()
            ->with(['media', 'vendorProfile'])
            ->latest()
            ->paginate();

        return Response::json(ShortVideoData::collect($videos));
    }

    public function show(ShortVideo $shortVideo): JsonResponse
    {
        $shortVideo->load(['media', 'vendorProfile', 'products']);

        return Response::json([
            'data' => ShortVideoData::fromModel($shortVideo),
        ]);
    }

    public function comments(ShortVideo $shortVideo): JsonResponse
    {
        $comments = $shortVideo->comments()
            ->with('user')
            ->latest()
            ->paginate();

        return Response::json(CommentData::collect(
            $comments->through(fn (ShortVideoComment $c) => CommentData::fromShortVideoComment($c))
        ));
    }

    public function storeComment(
        StoreCommentData $data,
        ShortVideo $shortVideo,
        #[CurrentUser] User $user,
    ): JsonResponse {
        $comment = $shortVideo->comments()->create([
            'user_id' => $user->getKey(),
            'comment' => $data->comment,
        ]);

        $comment->load('user');

        return Response::json(
            ['data' => CommentData::fromShortVideoComment($comment)],
            HttpResponse::HTTP_CREATED
        );
    }

    public function destroyComment(
        ShortVideo $shortVideo,
        ShortVideoComment $comment,
        #[CurrentUser] User $user,
    ): JsonResponse {
        abort_unless($comment->shortVideo()->is($shortVideo), HttpResponse::HTTP_NOT_FOUND);
        abort_unless($comment->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $comment->delete();

        return Response::json(['message' => 'Comment deleted.']);
    }

    public function like(ShortVideo $shortVideo, #[CurrentUser] User $user): JsonResponse
    {
        $shortVideo->likes()->firstOrCreate(['user_id' => $user->getKey()]);
        $shortVideo->increment('likes_count');

        return Response::json(['message' => 'Liked.']);
    }

    public function save(ShortVideo $shortVideo, #[CurrentUser] User $user): JsonResponse
    {
        $shortVideo->saves()->firstOrCreate(['user_id' => $user->getKey()]);

        return Response::json(['message' => 'Saved.']);
    }
}
