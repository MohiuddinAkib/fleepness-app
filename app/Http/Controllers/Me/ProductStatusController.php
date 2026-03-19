<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\Product;
use App\Enums\ProductStatus;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group('Products', 'Vendor product status management. Requires an approved vendor profile.')]
class ProductStatusController extends Controller
{
    #[Authenticated]
    #[Endpoint('Update product status', 'Toggles the product between active and inactive status.')]
    #[Response('{"data": {"status": "inactive"}}', 200)]
    public function update(
        Product $product,
        #[CurrentUser] User $user,
    ): JsonResponse|Responsable {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN);
        abort_unless($product->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $product->update([
            'status' => $product->is_active
                ? ProductStatus::Inactive
                : ProductStatus::Active,
        ]);

        return response()->json([
            'data' => [
                'status' => $product->fresh()->status,
            ],
        ]);
    }
}
