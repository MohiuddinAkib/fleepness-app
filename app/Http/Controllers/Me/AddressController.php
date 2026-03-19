<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\Address;
use App\Data\AddressData;
use App\Data\Me\StoreAddressData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Spatie\LaravelData\DataCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\BodyParam;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group('Addresses', 'Manage delivery addresses for the authenticated user.')]
class AddressController extends Controller
{
    #[Authenticated]
    #[Endpoint('List addresses')]
    #[Response('{"data": [{"id": 1, "label": "Home", "formatted_address": "123 Main St", "is_default": true}]}', 200)]
    public function index(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $addresses = $user->addresses()->get();

        return AddressData::collect($addresses, DataCollection::class);
    }

    #[Authenticated]
    #[BodyParam('label', 'string', required: false, example: 'Home')]
    #[BodyParam('formatted_address', 'string', required: false, example: null)]
    #[BodyParam('address_line_1', 'string', required: false, example: null)]
    #[BodyParam('city', 'string', required: false, example: null)]
    #[BodyParam('postal_code', 'string', required: false, example: null)]
    #[BodyParam('latitude', 'number', required: false, example: 23.8103)]
    #[BodyParam('longitude', 'number', required: false, example: 90.4125)]
    #[BodyParam('is_default', 'boolean', required: false, example: false)]
    #[Endpoint('Add address')]
    #[Response('{"data": {"id": 2, "label": "Home", "is_default": false}}', 201)]
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

    #[Authenticated]
    #[BodyParam('label', 'string', required: false, example: 'Home')]
    #[BodyParam('formatted_address', 'string', required: false, example: null)]
    #[BodyParam('address_line_1', 'string', required: false, example: null)]
    #[BodyParam('city', 'string', required: false, example: null)]
    #[BodyParam('postal_code', 'string', required: false, example: null)]
    #[BodyParam('latitude', 'number', required: false, example: 23.8103)]
    #[BodyParam('longitude', 'number', required: false, example: 90.4125)]
    #[BodyParam('is_default', 'boolean', required: false, example: false)]
    #[Endpoint('Update address')]
    #[Response('{"data": {"id": 1, "label": "Work"}}', 200)]
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

    #[Authenticated]
    #[Endpoint('Delete address')]
    #[Response('{"message": "Address deleted."}', 200)]
    public function destroy(Address $address, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        abort_unless($address->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $address->delete();

        return response()->json(['message' => 'Address removed.']);
    }

    #[Authenticated]
    #[Endpoint('Set default address', 'Marks the specified address as the default delivery address.')]
    #[Response('{"message": "Default address updated."}', 200)]
    public function setDefault(Address $address, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        abort_unless($address->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $user->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return response()->json(['message' => 'Default address updated.', 'data' => AddressData::fromModel($address->fresh())]);
    }

    #[Authenticated]
    #[Endpoint('Get default address', 'Legacy compatibility alias for `/api/addresses/default`. Prefer `/api/me/addresses` plus the `is_default` flag, or `/api/me/addresses/{address}/default` for updates in new clients.')]
    #[Response('{"default_address":{"id":1,"label":"Home","is_default":true}}', 200)]
    /**
     * Legacy alias for the historical `/api/addresses/default` contract.
     *
     * Preferred modern path:
     * - read `/api/me/addresses` and pick the item with `is_default = true`
     * - update via `/api/me/addresses/{address}/default`
     *
     * This method remains only to keep the older React Native client working during migration.
     */
    public function default(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $address = $user->defaultAddress()->first();

        return response()->json([
            'default_address' => null === $address ? null : AddressData::fromModel($address),
            'data' => null === $address ? null : AddressData::fromModel($address),
        ]);
    }
}
