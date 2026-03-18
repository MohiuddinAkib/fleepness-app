<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\Address;
use App\Data\AddressData;
use App\Attributes\CurrentUser;
use App\Data\Me\StoreAddressData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AddressController extends Controller
{
    public function index(#[CurrentUser] User $user): JsonResponse
    {
        $addresses = $user->addresses()->get();

        return Response::json([
            'data' => AddressData::collect($addresses),
        ]);
    }

    public function store(StoreAddressData $data, #[CurrentUser] User $user): JsonResponse
    {
        $address = $user->addresses()->create([
            'label' => $data->label,
            'formatted_address' => $data->formattedAddress,
            'address_line_1' => $data->addressLine1,
            'address_line_2' => $data->addressLine2,
            'area' => $data->area,
            'city' => $data->city,
            'postal_code' => $data->postalCode,
            'latitude' => $data->latitude,
            'longitude' => $data->longitude,
            'is_default' => false,
        ]);

        return Response::json([
            'message' => 'Address added.',
            'data' => AddressData::fromModel($address),
        ], HttpResponse::HTTP_CREATED);
    }

    public function update(StoreAddressData $data, Address $address, #[CurrentUser] User $user): JsonResponse
    {
        abort_unless($address->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $address->update([
            'label' => $data->label,
            'formatted_address' => $data->formattedAddress,
            'address_line_1' => $data->addressLine1,
            'address_line_2' => $data->addressLine2,
            'area' => $data->area,
            'city' => $data->city,
            'postal_code' => $data->postalCode,
            'latitude' => $data->latitude,
            'longitude' => $data->longitude,
        ]);

        return Response::json([
            'message' => 'Address updated.',
            'data' => AddressData::fromModel($address->fresh()),
        ]);
    }

    public function destroy(Address $address, #[CurrentUser] User $user): JsonResponse
    {
        abort_unless($address->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $address->delete();

        return Response::json(['message' => 'Address removed.']);
    }

    public function setDefault(Address $address, #[CurrentUser] User $user): JsonResponse
    {
        abort_unless($address->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $user->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return Response::json([
            'message' => 'Default address updated.',
            'data' => AddressData::fromModel($address->fresh()),
        ]);
    }
}
