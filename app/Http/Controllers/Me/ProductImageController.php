<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ProductImageController extends Controller
{
    #[Authenticated]
    #[Endpoint('Delete product image')]
    #[Response('{"message": "Image deleted."}', 200)]
    public function destroy(
        Product $product,
        int $mediaId,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($product->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $media = $product->getMedia('images')->firstWhere('id', $mediaId);
        abort_if(null === $media, HttpResponse::HTTP_NOT_FOUND);

        $media->delete();

        return response()->json(['message' => 'Image deleted.']);
    }
}
