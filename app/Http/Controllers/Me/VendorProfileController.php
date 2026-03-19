<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Enums\VendorStatus;
use App\Data\VendorProfileData;
use Spatie\LaravelData\Optional;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Data\Me\VendorApplicationData;
use App\Data\Me\UpdateVendorProfileData;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class VendorProfileController extends Controller
{
    public function show(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $vendorProfile = $user->vendorProfile;

        abort_if(null === $vendorProfile, HttpResponse::HTTP_NOT_FOUND, 'No vendor profile found.');

        return VendorProfileData::fromModel($vendorProfile);
    }

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
