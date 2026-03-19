<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\User;
use App\Data\CommentData;
use App\Data\ProductData;
use App\Models\ShortVideo;
use App\Data\ShortVideoData;
use App\Models\ShortVideoComment;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\LaravelData\DataCollection;
use App\Data\ShortVideo\StoreCommentData;
use Illuminate\Contracts\Support\Responsable;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ShortVideoController extends Controller
{
    public function index(): JsonResponse|Responsable
    {
        $videos = ShortVideo::query()
            ->with(['media', 'vendorProfile'])
            ->latest()
            ->paginate();

        return ShortVideoData::collect($videos, PaginatedDataCollection::class);
    }

    public function show(ShortVideo $shortVideo): JsonResponse|Responsable
    {
        $shortVideo->load(['media', 'vendorProfile', 'products']);

        return ShortVideoData::fromModel($shortVideo);
    }

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

    public function like(ShortVideo $shortVideo, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $shortVideo->likes()->firstOrCreate(['user_id' => $user->getKey()]);
        $shortVideo->increment('likes_count');

        return response()->json(['message' => 'Liked.']);
    }

    public function save(ShortVideo $shortVideo, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $shortVideo->saves()->firstOrCreate(['user_id' => $user->getKey()]);

        return response()->json(['message' => 'Saved.']);
    }

    public function products(ShortVideo $shortVideo): JsonResponse|Responsable
    {
        $products = $shortVideo->products()->with(['media', 'vendorProfile', 'category'])->get();

        return ProductData::collect($products, DataCollection::class);
    }
}
