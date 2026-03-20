<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\Livestream;
use Illuminate\Support\Str;
use App\Data\LivestreamData;
use App\Enums\LivestreamStatus;
use Spatie\LaravelData\Optional;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use App\Data\Response\TokenResponseData;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\BodyParam;
use App\Data\Response\MessageResponseData;
use App\Data\Dto\GeneratePublisherTokenData;
use App\Data\Livestream\StoreLivestreamData;
use App\Data\Livestream\UpdateLivestreamData;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use App\Facades\Livestream as LivestreamFacade;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use App\Data\Response\Livestream\LivestreamSessionResponseData;

#[Group('Livestreams', 'Vendor livestream management. Creating a livestream immediately starts it on LiveKit and returns a publisher token.')]
class LivestreamController extends Controller
{
    #[Authenticated]
    #[Endpoint('List own livestreams')]
    #[Response('{"data":[{"id":1,"title":"Flash Sale","status":"started"}],"meta":{"current_page":1}}', 200)]
    public function index(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $livestreams = Livestream::query()
            ->where('vendor_profile_id', $user->vendorProfile?->getKey())
            ->with(['media', 'vendorProfile'])
            ->latest()
            ->paginate();

        return LivestreamData::collect(
            $livestreams,
            PaginatedDataCollection::class,
        );
    }

    #[Authenticated]
    #[BodyParam('title', 'string', required: true, example: 'Friday Flash Sale')]
    #[BodyParam('description', 'string', required: false, nullable: true, example: 'Huge discounts on all items')]
    #[BodyParam('scheduled_at', 'string', required: false, nullable: true, example: '2026-03-25 18:00:00')]
    #[Endpoint('Create & start a livestream', 'Creates a new livestream with status=started, begins LiveKit egress recording, and returns a publisher token to connect to the room.')]
    #[Response('{"data":{"id":1,"title":"Friday Flash Sale","status":"started","room_name":"livestream_1"},"token":"eyJhbGci..."}', 201)]
    /** @return LivestreamSessionResponseData */
    public function store(
        StoreLivestreamData $data,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);

        $livestream = Livestream::query()->create([
            'vendor_profile_id' => $vendorProfile->getKey(),
            'title' => $data->title,
            'description' => $data->description instanceof Optional
                    ? null
                    : $data->description,
            'room_id' => Str::uuid()->toString(),
            'status' => LivestreamStatus::Started,
            'started_at' => now(),
            'scheduled_at' => $data->scheduledAt instanceof Optional
                    ? null
                    : $data->scheduledAt,
        ]);

        $token = LivestreamFacade::generatePublisherToken(
            new GeneratePublisherTokenData(
                roomName: $livestream->room_name,
                identity: (string) $user->getKey(),
                displayName: $user->name ?? 'Vendor',
                metadata: ['livestream_id' => $livestream->getKey()],
            ),
        );

        $livestream->startRecording();
        $livestream->load(['media', 'vendorProfile']);

        return response()->json(LivestreamSessionResponseData::from([
            'data' => LivestreamData::fromModel($livestream),
            'token' => $token,
        ])->toArray(), HttpResponse::HTTP_CREATED);
    }

    #[Authenticated]
    #[BodyParam('title', 'string', required: false)]
    #[BodyParam('description', 'string', required: false, nullable: true)]
    #[BodyParam('scheduled_at', 'string', required: false, nullable: true)]
    #[BodyParam('status', 'string', required: false, enum: ['started', 'finished'], example: 'finished')]
    #[Endpoint('Update a livestream', 'Update title/description, or transition status. Use status=started to go live (from scheduled), status=finished to end the stream.')]
    #[Response('{"data":{"id":1,"status":"finished","total_duration":3600}}', 200)]
    /** @return LivestreamSessionResponseData */
    public function update(
        UpdateLivestreamData $data,
        Livestream $livestream,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless(
            $livestream->vendorProfile()->is($vendorProfile),
            HttpResponse::HTTP_FORBIDDEN,
        );
        abort_if(
            $livestream->status->isFinished(),
            HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
        );

        $updates = [];
        $token = null;

        if (! $data->title instanceof Optional) {
            $updates['title'] = $data->title;
        }

        if (! $data->description instanceof Optional) {
            $updates['description'] = $data->description;
        }

        if (! $data->scheduledAt instanceof Optional) {
            $updates['scheduled_at'] = $data->scheduledAt;
        }

        if (! $data->status instanceof Optional) {
            if ($data->status->isStarted()) {
                abort_unless(
                    $livestream->status->isScheduled(),
                    HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
                );
                $updates['status'] = LivestreamStatus::Started;
                $updates['started_at'] = now();
                $token = LivestreamFacade::generatePublisherToken(
                    new GeneratePublisherTokenData(
                        roomName: $livestream->room_name,
                        identity: (string) $user->getKey(),
                        displayName: $user->name ?? 'Vendor',
                        metadata: ['livestream_id' => $livestream->getKey()],
                    ),
                );
            } elseif ($data->status->isFinished()) {
                $updates['status'] = LivestreamStatus::Finished;
                $updates['ended_at'] = now();
                if (null !== $livestream->started_at) {
                    $updates['total_duration'] = (int) $livestream->started_at->diffInSeconds(now());
                }
            }
        }

        $livestream->update($updates);

        if (! $data->status instanceof Optional && $data->status->isStarted()) {
            $livestream->startRecording();
        }

        if (! $data->status instanceof Optional && $data->status->isFinished()) {
            $livestream->stopRecording();
        }
        $livestream->load(['media', 'vendorProfile']);

        return response()->json(LivestreamSessionResponseData::from([
            'data' => LivestreamData::fromModel($livestream),
            'token' => $token,
        ])->toArray());
    }

    #[Authenticated]
    #[Endpoint('Delete a scheduled livestream', 'Can only delete livestreams with status=scheduled.')]
    #[Response('{"message":"Livestream deleted."}', 200)]
    /** @return MessageResponseData */
    public function destroy(
        Livestream $livestream,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless(
            $livestream->vendorProfile()->is($vendorProfile),
            HttpResponse::HTTP_FORBIDDEN,
        );
        abort_unless(
            $livestream->status->isScheduled(),
            HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
        );

        $livestream->delete();

        return response()->json(MessageResponseData::from([
            'message' => 'Livestream deleted.',
        ])->toArray());
    }

    #[Authenticated]
    #[Endpoint('Get publisher token', 'Generates a fresh LiveKit publisher token for the vendor to (re)connect to the room.')]
    #[Response('{"token":"eyJhbGci..."}', 200)]
    /** @return TokenResponseData */
    public function publisherToken(
        Livestream $livestream,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless(
            $livestream->vendorProfile()->is($vendorProfile),
            HttpResponse::HTTP_FORBIDDEN,
        );
        abort_if(
            $livestream->status->isFinished(),
            HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
        );

        $data = new GeneratePublisherTokenData(
            roomName: $livestream->room_name,
            identity: (string) $user->getKey(),
            displayName: $user->name ?? 'Vendor',
            metadata: ['livestream_identity' => $livestream->getKey()],
        );

        $token = LivestreamFacade::generatePublisherToken($data);

        return response()->json(TokenResponseData::from([
            'token' => $token,
        ])->toArray());
    }
}
