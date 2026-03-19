<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\Product;
use App\Models\Livestream;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\BodyParam;
use App\Data\Livestream\AttachProductData;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class LivestreamProductController extends Controller
{
    public function index(Livestream $livestream): never
    {
        abort(HttpResponse::HTTP_NOT_FOUND);
    }

    #[Authenticated]
    #[BodyParam('product_id', 'integer', required: true, example: 5)]
    #[Endpoint('Attach product to livestream')]
    #[Response('{"message":"Product attached."}', 200)]
    public function store(
        AttachProductData $data,
        Livestream $livestream,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($livestream->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $livestream->products()->syncWithoutDetaching([$data->productId]);

        return response()->json(['message' => 'Product attached.']);
    }

    public function show(Livestream $livestream, Product $product): never
    {
        abort(HttpResponse::HTTP_NOT_FOUND);
    }

    public function update(Livestream $livestream, Product $product): never
    {
        abort(HttpResponse::HTTP_NOT_FOUND);
    }

    #[Authenticated]
    #[Endpoint('Detach product from livestream')]
    #[Response('{"message":"Product detached."}', 200)]
    public function destroy(
        Livestream $livestream,
        Product $product,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($livestream->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $livestream->products()->detach($product->getKey());

        return response()->json(['message' => 'Product detached.']);
    }
}
