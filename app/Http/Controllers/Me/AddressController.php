<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\Address;
use App\Data\AddressData;
use App\Data\Me\StoreAddressData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\LaravelData\DataCollection;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AddressController extends Controller
{
    public function index(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $addresses = $user->addresses()->get();

        return AddressData::collect($addresses, DataCollection::class);
    }

    public function store(StoreAddressData $data, #[CurrentUser] User $user): JsonResponse|Responsable
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

        return AddressData::fromModel($address)->additional(['message' => 'Address added.']);
    }

    public function update(StoreAddressData $data, Address $address, #[CurrentUser] User $user): JsonResponse|Responsable
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

        return AddressData::fromModel($address->fresh())->additional(['message' => 'Address updated.']);
    }

    public function destroy(Address $address, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        abort_unless($address->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $address->delete();

        return response()->json(['message' => 'Address removed.']);
    }

    public function setDefault(Address $address, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        abort_unless($address->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $user->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return response()->json(['message' => 'Default address updated.', 'data' => AddressData::fromModel($address->fresh())]);
    }
}
