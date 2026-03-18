<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\Livestream;
use Illuminate\Support\Str;
use App\Data\LivestreamData;
use App\Attributes\CurrentUser;
use App\Enums\LivestreamStatus;
use Spatie\LaravelData\Optional;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Data\Livestream\StoreLivestreamData;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class LivestreamController extends Controller
{
    public function index(#[CurrentUser] User $user): JsonResponse
    {
        $livestreams = Livestream::query()
            ->where('vendor_profile_id', $user->vendorProfile?->getKey())
            ->with(['media', 'vendorProfile'])
            ->latest()
            ->paginate();

        return Response::json(LivestreamData::collect($livestreams));
    }

    public function store(
        StoreLivestreamData $data,
        #[CurrentUser] User $user,
    ): JsonResponse {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);

        $livestream = Livestream::query()->create([
            'vendor_profile_id' => $vendorProfile->getKey(),
            'title' => $data->title,
            'description' => $data->description instanceof Optional ? null : $data->description,
            'room_id' => Str::uuid()->toString(),
            'status' => LivestreamStatus::Scheduled,
            'scheduled_at' => $data->scheduledAt instanceof Optional ? null : $data->scheduledAt,
        ]);

        $livestream->load(['media', 'vendorProfile']);

        return Response::json(
            ['data' => LivestreamData::fromModel($livestream)],
            HttpResponse::HTTP_CREATED
        );
    }

    public function update(
        StoreLivestreamData $data,
        Livestream $livestream,
        #[CurrentUser] User $user,
    ): JsonResponse {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($livestream->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $livestream->update(array_filter([
            'title' => $data->title,
            'description' => $data->description instanceof Optional ? $livestream->description : $data->description,
            'scheduled_at' => $data->scheduledAt instanceof Optional ? $livestream->scheduled_at : $data->scheduledAt,
        ]));

        $livestream->load(['media', 'vendorProfile']);

        return Response::json(['data' => LivestreamData::fromModel($livestream)]);
    }

    public function destroy(
        Livestream $livestream,
        #[CurrentUser] User $user,
    ): JsonResponse {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($livestream->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $livestream->delete();

        return Response::json(['message' => 'Livestream deleted.']);
    }
}
