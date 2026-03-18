<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Enums\VendorStatus;
use App\Attributes\CurrentUser;
use App\Data\VendorProfileData;
use Spatie\LaravelData\Optional;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Data\Me\VendorApplicationData;
use App\Data\Me\UpdateVendorProfileData;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class VendorProfileController extends Controller
{
    public function show(#[CurrentUser] User $user): JsonResponse
    {
        $vendorProfile = $user->vendorProfile;

        abort_if(null === $vendorProfile, HttpResponse::HTTP_NOT_FOUND, 'No vendor profile found.');

        return Response::json([
            'data' => VendorProfileData::fromModel($vendorProfile),
        ]);
    }

    public function update(UpdateVendorProfileData $data, #[CurrentUser] User $user): JsonResponse
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

        return Response::json([
            'message' => 'Vendor profile updated.',
            'data' => VendorProfileData::fromModel($vendorProfile->fresh()),
        ]);
    }

    public function balance(#[CurrentUser] User $user): JsonResponse
    {
        $vendorProfile = $user->vendorProfile;

        abort_if(null === $vendorProfile, HttpResponse::HTTP_NOT_FOUND, 'No vendor profile found.');

        return Response::json([
            'data' => [
                'balance' => $vendorProfile->balance,
                'total_sales' => $vendorProfile->total_sales,
                'withdrawn_amount' => $vendorProfile->withdrawn_amount,
            ],
        ]);
    }

    public function apply(VendorApplicationData $data, #[CurrentUser] User $user): JsonResponse
    {
        abort_if(null !== $user->vendorProfile, HttpResponse::HTTP_UNPROCESSABLE_ENTITY, 'Already applied as a vendor.');

        $vendorProfile = $user->vendorProfile()->create([
            'shop_name' => $data->shopName,
            'description' => $data->description,
            'shop_category_id' => $data->shopCategoryId,
            'status' => VendorStatus::Pending,
        ]);

        return Response::json([
            'message' => 'Vendor application submitted.',
            'data' => VendorProfileData::fromModel($vendorProfile),
        ], HttpResponse::HTTP_CREATED);
    }

    public function applicationStatus(#[CurrentUser] User $user): JsonResponse
    {
        $vendorProfile = $user->vendorProfile;

        abort_if(null === $vendorProfile, HttpResponse::HTTP_NOT_FOUND, 'No vendor application found.');

        return Response::json([
            'data' => [
                'status' => $vendorProfile->status,
                'status_note' => $vendorProfile->status_note,
            ],
        ]);
    }
}
