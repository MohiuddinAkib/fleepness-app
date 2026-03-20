<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\Product;
use App\Models\ShortVideo;
use App\Data\ShortVideoData;
use Spatie\LaravelData\Optional;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\BodyParam;
use App\Data\Response\MessageResponseData;
use App\Data\ShortVideo\StoreShortVideoData;
use App\Data\ShortVideo\UpdateShortVideoData;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group('Short Videos', 'Vendor short-video management. Short videos are TikTok-style shoppable content.')]
class ShortVideoController extends Controller
{
    #[Authenticated]
    #[Endpoint('List own short videos')]
    #[Response('{"data":[{"id":1,"title":"New Collection Drop"}],"meta":{"current_page":1}}', 200)]
    /** @return PaginatedDataCollection<ShortVideoData> */
    public function index(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $videos = ShortVideo::query()
            ->where('vendor_profile_id', $user->vendorProfile?->getKey())
            ->with(['media', 'vendorProfile', 'products.media', 'products.vendorProfile', 'products.category'])
            ->latest()
            ->paginate();

        return ShortVideoData::collect($videos, PaginatedDataCollection::class);
    }

    #[Authenticated]
    #[BodyParam('title', 'string', required: true, example: 'Summer Collection')]
    #[BodyParam('description', 'string', required: false, nullable: true)]
    #[BodyParam('video', 'file', required: true, example: 'No-example')]
    #[BodyParam('thumbnail', 'file', required: false, nullable: true)]
    #[Endpoint('Upload short video')]
    #[Response('{"data":{"id":1,"title":"Summer Collection"}}', 201)]
    /** @return ShortVideoData */
    public function store(
        StoreShortVideoData $data,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);

        $video = ShortVideo::query()->create([
            'vendor_profile_id' => $vendorProfile->getKey(),
            'title' => $data->title,
            'description' => $data->description,
        ]);

        $video
            ->addMedia($data->video)
            ->toMediaCollection('video');

        if (null !== $data->thumbnail) {
            $video
                ->addMedia($data->thumbnail)
                ->toMediaCollection('thumbnail');
        }

        $this->syncProducts($video, $vendorProfile->getKey(), $data->productIds);

        $video->load(['media', 'vendorProfile', 'products.media', 'products.vendorProfile', 'products.category']);

        return ShortVideoData::fromModel($video);
    }

    #[Authenticated]
    #[BodyParam('title', 'string', required: false)]
    #[BodyParam('description', 'string', required: false, nullable: true)]
    #[Endpoint('Update short video')]
    #[Response('{"data":{"id":1,"title":"Updated Title"}}', 200)]
    /** @return ShortVideoData */
    public function update(
        UpdateShortVideoData $data,
        ShortVideo $shortVideo,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($shortVideo->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $attributes = [];

        if (! $data->title instanceof Optional) {
            $attributes['title'] = $data->title;
        }

        if (! $data->description instanceof Optional) {
            $attributes['description'] = $data->description;
        }

        if ([] !== $attributes) {
            $shortVideo->update($attributes);
        }

        if (! $data->video instanceof Optional) {
            $shortVideo
                ->addMedia($data->video)
                ->toMediaCollection('video');
        }

        if (! $data->thumbnail instanceof Optional) {
            if (null === $data->thumbnail) {
                $shortVideo->clearMediaCollection('thumbnail');
            } else {
                $shortVideo
                    ->addMedia($data->thumbnail)
                    ->toMediaCollection('thumbnail');
            }
        }

        if (! $data->productIds instanceof Optional) {
            $this->syncProducts($shortVideo, $vendorProfile->getKey(), $data->productIds);
        }

        $shortVideo->load(['media', 'vendorProfile', 'products.media', 'products.vendorProfile', 'products.category']);

        return ShortVideoData::fromModel($shortVideo);
    }

    #[Authenticated]
    #[Endpoint('Delete short video')]
    #[Response('{"message":"Short video deleted."}', 200)]
    /** @return MessageResponseData */
    public function destroy(
        ShortVideo $shortVideo,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($shortVideo->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $shortVideo->delete();

        return response()->json(MessageResponseData::from([
            'message' => 'Short video deleted.',
        ])->toArray());
    }

    /** @param array<int, int> $productIds */
    protected function syncProducts(ShortVideo $shortVideo, int $vendorProfileId, array $productIds): void
    {
        if ([] === $productIds) {
            $shortVideo->products()->sync([]);

            return;
        }

        $ownedProductIds = Product::query()
            ->where('vendor_profile_id', $vendorProfileId)
            ->whereKey($productIds)
            ->pluck('id')
            ->all();

        abort_unless(count($ownedProductIds) === count(array_unique($productIds)), HttpResponse::HTTP_UNPROCESSABLE_ENTITY);

        $shortVideo->products()->sync($ownedProductIds);
    }
}
