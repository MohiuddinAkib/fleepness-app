<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\User;
use App\Data\CommentData;
use App\Models\Livestream;
use App\Data\LivestreamData;
use App\Attributes\CurrentUser;
use App\Models\LivestreamComment;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Data\Livestream\StoreCommentData;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class LivestreamController extends Controller
{
    public function index(): JsonResponse
    {
        $livestreams = Livestream::query()
            ->with(['media', 'vendorProfile'])
            ->latest()
            ->paginate();

        return Response::json(LivestreamData::collect($livestreams));
    }

    public function show(Livestream $livestream): JsonResponse
    {
        $livestream->load(['media', 'vendorProfile', 'products']);

        return Response::json([
            'data' => LivestreamData::fromModel($livestream),
        ]);
    }

    public function comments(Livestream $livestream): JsonResponse
    {
        $comments = $livestream->comments()
            ->with('user')
            ->latest()
            ->paginate();

        return Response::json(CommentData::collect(
            $comments->through(fn (LivestreamComment $c) => CommentData::fromLivestreamComment($c))
        ));
    }

    public function storeComment(
        StoreCommentData $data,
        Livestream $livestream,
        #[CurrentUser] User $user,
    ): JsonResponse {
        $comment = $livestream->comments()->create([
            'user_id' => $user->getKey(),
            'comment' => $data->comment,
        ]);

        $comment->load('user');

        return Response::json(
            ['data' => CommentData::fromLivestreamComment($comment)],
            HttpResponse::HTTP_CREATED
        );
    }

    public function destroyComment(
        Livestream $livestream,
        LivestreamComment $comment,
        #[CurrentUser] User $user,
    ): JsonResponse {
        abort_unless($comment->livestream()->is($livestream), HttpResponse::HTTP_NOT_FOUND);
        abort_unless($comment->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $comment->delete();

        return Response::json(['message' => 'Comment deleted.']);
    }

    public function like(Livestream $livestream, #[CurrentUser] User $user): JsonResponse
    {
        $livestream->likes()->firstOrCreate(['user_id' => $user->getKey()]);

        return Response::json(['message' => 'Liked.']);
    }

    public function save(Livestream $livestream, #[CurrentUser] User $user): JsonResponse
    {
        $livestream->saves()->firstOrCreate(['user_id' => $user->getKey()]);

        return Response::json(['message' => 'Saved.']);
    }
}
