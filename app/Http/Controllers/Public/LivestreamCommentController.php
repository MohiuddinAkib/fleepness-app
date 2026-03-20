<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Models\User;
use App\Data\CommentData;
use App\Models\Livestream;
use App\Models\LivestreamComment;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use App\Data\Livestream\StoreCommentData;
use Knuckles\Scribe\Attributes\BodyParam;
use App\Data\Livestream\UpdateCommentData;
use App\Data\Response\MessageResponseData;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Unauthenticated;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group('Content', 'Browse and manage livestream comments through the nested livestream comment resource.')]
class LivestreamCommentController extends Controller
{
    #[Endpoint('List livestream comments')]
    #[Response('{"data":[{"id":1,"comment":"Watching now!"}],"meta":{"current_page":1}}', 200)]
    #[Unauthenticated]
    public function index(Livestream $livestream): JsonResponse|Responsable
    {
        $comments = $livestream->comments()
            ->with('user')
            ->latest()
            ->paginate();

        return CommentData::collect(
            $comments->through(fn (LivestreamComment $comment) => CommentData::fromLivestreamComment($comment)),
            PaginatedDataCollection::class
        );
    }

    #[Authenticated]
    #[BodyParam('comment', 'string', required: true, example: 'Watching now!')]
    #[Endpoint('Post livestream comment')]
    #[Response('{"data":{"id":1,"comment":"Watching now!"}}', 201)]
    public function store(
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

    #[Authenticated]
    #[BodyParam('comment', 'string', required: true, example: 'Updated message')]
    #[Endpoint('Update livestream comment')]
    #[Response('{"data":{"id":1,"comment":"Updated message"}}', 200)]
    public function update(
        UpdateCommentData $data,
        Livestream $livestream,
        LivestreamComment $comment,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        abort_unless($comment->livestream()->is($livestream), HttpResponse::HTTP_NOT_FOUND);
        abort_unless($comment->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $comment->update([
            'comment' => $data->comment,
        ]);

        $comment->load('user');

        return CommentData::fromLivestreamComment($comment);
    }

    #[Authenticated]
    #[Endpoint('Delete livestream comment')]
    #[Response('{"message":"Comment deleted."}', 200)]
    /** @return MessageResponseData */
    public function destroy(
        Livestream $livestream,
        LivestreamComment $comment,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        abort_unless($comment->livestream()->is($livestream), HttpResponse::HTTP_NOT_FOUND);
        abort_unless($comment->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $comment->delete();

        return response()->json(MessageResponseData::from([
            'message' => 'Comment deleted.',
        ])->toArray());
    }
}
