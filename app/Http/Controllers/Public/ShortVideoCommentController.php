<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\User;
use App\Data\CommentData;
use App\Models\ShortVideo;
use App\Models\ShortVideoComment;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
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

#[Group('Content', 'Browse and manage short video comments through the nested short video comment resource.')]
class ShortVideoCommentController extends Controller
{
    #[Endpoint('List short video comments')]
    #[Response('{"data":[{"id":1,"comment":"Great video!"}],"meta":{"current_page":1}}', 200)]
    #[Unauthenticated]
    public function index(ShortVideo $shortVideo): JsonResponse|Responsable
    {
        $comments = $shortVideo->comments()
            ->with('user')
            ->latest()
            ->paginate();

        return CommentData::collect(
            $comments->through(fn (ShortVideoComment $comment) => CommentData::fromShortVideoComment($comment)),
            PaginatedDataCollection::class
        );
    }

    #[Authenticated]
    #[BodyParam('comment', 'string', required: true, example: 'Great video!')]
    #[Endpoint('Post short video comment')]
    #[Response('{"data":{"id":1,"comment":"Great video!"}}', 201)]
    public function store(
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

    public function show(ShortVideo $shortVideo, ShortVideoComment $shortVideoComment): never
    {
        abort(HttpResponse::HTTP_NOT_FOUND);
    }

    public function update(ShortVideo $shortVideo, ShortVideoComment $shortVideoComment): never
    {
        abort(HttpResponse::HTTP_NOT_FOUND);
    }

    #[Authenticated]
    #[Endpoint('Delete short video comment')]
    #[Response('{"message":"Comment deleted."}', 200)]
    public function destroy(
        ShortVideo $shortVideo,
        ShortVideoComment $comment,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        abort_unless($comment->shortVideo()->is($shortVideo), HttpResponse::HTTP_NOT_FOUND);
        abort_unless($comment->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $comment->delete();

        return response()->json(['message' => 'Comment deleted.']);
    }
}
