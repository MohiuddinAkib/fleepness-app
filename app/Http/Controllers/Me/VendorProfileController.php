<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Enums\VendorStatus;
use App\Data\VendorProfileData;
use Spatie\LaravelData\Optional;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use App\Data\Me\VendorApplicationData;
use App\Data\Me\UpdateVendorProfileData;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\BodyParam;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group('Vendor Profile', 'Manage your vendor/seller profile, apply to become a vendor, and check application status.')]
class VendorProfileController extends Controller
{
    #[Authenticated]
    #[Endpoint('Get vendor profile', 'Returns the authenticated user\'s vendor profile. Returns 404 if not a vendor.')]
    #[Response('{"data": {"id": 1, "shop_name": "My Shop", "status": "approved", "balance": "150.00"}}', 200)]
    public function show(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $vendorProfile = $user->vendorProfile;

        abort_if(null === $vendorProfile, HttpResponse::HTTP_NOT_FOUND, 'No vendor profile found.');

        return VendorProfileData::fromModel($vendorProfile);
    }

    #[Authenticated]
    #[BodyParam('shop_name', 'string', required: false, example: 'My Cool Shop')]
    #[BodyParam('description', 'string', required: false, example: null)]
    #[BodyParam('pickup_location', 'string', required: false, example: null)]
    #[Endpoint('Update vendor profile')]
    #[Response('{"data": {"id": 1, "shop_name": "My Cool Shop"}}', 200)]
    public function update(UpdateVendorProfileData $data, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $vendorProfile = $user->vendorProfile;

        abort_if(null === $vendorProfile, HttpResponse::HTTP_NOT_FOUND, 'No vendor profile found.');

        $updates = [];

        if (! $data->shopName instanceof Optional) {
            $updates['shop_name'] = $data->shopName;
        }

        if (! $data->description instanceof Optional) {
            $updates['description'] = $data->description;
        }

        if (! $data->pickupLocation instanceof Optional) {
            $updates['pickup_location'] = $data->pickupLocation;
        }

        if (! $data->shopCategoryId instanceof Optional) {
            $updates['shop_category_id'] = $data->shopCategoryId;
        }

        if ([] !== $updates) {
            $vendorProfile->update($updates);
        }

        return VendorProfileData::fromModel($vendorProfile->fresh())->additional(['message' => 'Vendor profile updated.']);
    }

    #[Authenticated]
    #[Endpoint('Get vendor balance', 'Returns balance, total sales, withdrawn amount and pending withdrawal for the vendor.')]
    #[Response('{"data": {"balance": "150.00", "total_sales": "1200.00", "withdrawn_amount": "500.00"}}', 200)]
    public function balance(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $vendorProfile = $user->vendorProfile;

        abort_if(null === $vendorProfile, HttpResponse::HTTP_NOT_FOUND, 'No vendor profile found.');

        return response()->json([
            'data' => [
                'balance' => $vendorProfile->balance,
                'total_sales' => $vendorProfile->total_sales,
                'withdrawn_amount' => $vendorProfile->withdrawn_amount,
            ],
        ]);
    }

    #[Authenticated]
    #[BodyParam('shop_name', 'string', required: true, example: 'Flash Store')]
    #[BodyParam('shop_category_id', 'integer', required: false, example: null)]
    #[Endpoint('Apply to become a vendor')]
    #[Response('{"message": "Application submitted.", "data": {"status": "pending"}}', 201)]
    public function apply(VendorApplicationData $data, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        abort_if(null !== $user->vendorProfile, HttpResponse::HTTP_UNPROCESSABLE_ENTITY, 'Already applied as a vendor.');

        $vendorProfile = $user->vendorProfile()->create([
            'shop_name' => $data->shopName,
            'description' => $data->description,
            'shop_category_id' => $data->shopCategoryId,
            'status' => VendorStatus::Pending,
        ]);

        return VendorProfileData::fromModel($vendorProfile)->additional(['message' => 'Vendor application submitted.']);
    }

    #[Authenticated]
    #[Endpoint('Check vendor application status')]
    #[Response('{"data": {"status": "pending"}}', 200)]
    public function applicationStatus(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $vendorProfile = $user->vendorProfile;

        abort_if(null === $vendorProfile, HttpResponse::HTTP_NOT_FOUND, 'No vendor application found.');

        return response()->json([
            'data' => [
                'status' => $vendorProfile->status,
                'status_note' => $vendorProfile->status_note,
            ],
        ]);
    }
}
