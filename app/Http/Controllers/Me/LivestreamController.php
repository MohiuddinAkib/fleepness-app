<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\Product;
use App\Models\Livestream;
use Illuminate\Support\Str;
use App\Data\LivestreamData;
use App\Enums\LivestreamStatus;
use Spatie\LaravelData\Optional;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Data\Livestream\AttachProductData;
use App\Data\Dto\GeneratePublisherTokenData;
use App\Data\Livestream\StoreLivestreamData;
use App\Data\Livestream\UpdateLivestreamData;
use Illuminate\Contracts\Support\Responsable;
use App\Facades\Livestream as LivestreamFacade;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class LivestreamController extends Controller
{
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
                roomName: $livestream->getRoomName(),
                identity: (string) $user->getKey(),
                displayName: $user->name ?? 'Vendor',
                metadata: ['livestream_id' => $livestream->getKey()],
            ),
        );

        $egress = LivestreamFacade::startRecording(
            $livestream->getRoomName(),
            sprintf('livestreams/%s/%s', $livestream->getRoomName(), now()->timestamp),
        );

        $livestream->update(['egress_id' => $egress->getEgressId()]);
        $livestream->load(['media', 'vendorProfile']);

        return LivestreamData::fromModel($livestream)->additional(['token' => $token]);
    }

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
                        roomName: $livestream->getRoomName(),
                        identity: (string) $user->getKey(),
                        displayName: $user->name ?? 'Vendor',
                        metadata: ['livestream_id' => $livestream->getKey()],
                    ),
                );
                $egress = LivestreamFacade::startRecording(
                    $livestream->getRoomName(),
                    sprintf('livestreams/%s/%s', $livestream->getRoomName(), now()->timestamp),
                );
                $updates['egress_id'] = $egress->getEgressId();
            } elseif ($data->status->isFinished()) {
                $updates['status'] = LivestreamStatus::Finished;
                $updates['ended_at'] = now();
                if (null !== $livestream->started_at) {
                    $updates[
                        'total_duration'
                    ] = (int) $livestream->started_at->diffInSeconds(now());
                }
                if (null !== $livestream->egress_id) {
                    LivestreamFacade::stopRecording($livestream->egress_id);
                }
            }
        }

        $livestream->update($updates);
        $livestream->load(['media', 'vendorProfile']);

        return LivestreamData::fromModel($livestream)
            ->when($token)
            ->additional(['token' => $token]);
    }

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

        return response()->json(['message' => 'Livestream deleted.']);
    }

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
            roomName: $livestream->getRoomName(),
            identity: (string) $user->getKey(),
            displayName: $user->name ?? 'Vendor',
            metadata: ['livestream_identity' => $livestream->getKey()],
        );

        $token = LivestreamFacade::generatePublisherToken($data);

        return response()->json(['token' => $token]);
    }

    public function attachProduct(
        AttachProductData $data,
        Livestream $livestream,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless(
            $livestream->vendorProfile()->is($vendorProfile),
            HttpResponse::HTTP_FORBIDDEN,
        );

        $livestream->products()->syncWithoutDetaching([$data->productId]);

        return response()->json(['message' => 'Product attached.']);
    }

    public function detachProduct(
        Livestream $livestream,
        Product $product,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless(
            $livestream->vendorProfile()->is($vendorProfile),
            HttpResponse::HTTP_FORBIDDEN,
        );

        $livestream->products()->detach($product->getKey());

        return response()->json(['message' => 'Product detached.']);
    }
}
