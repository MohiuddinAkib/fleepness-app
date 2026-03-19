<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\VendorOrder;
use App\Data\VendorOrderData;
use App\Enums\VendorOrderStatus;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use App\Notifications\VendorOrderStatusChanged;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group('Vendor Orders', 'Vendor order management — accept, reject and track orders assigned to the vendor.')]
class VendorOrderController extends Controller
{
    #[Authenticated]
    #[Endpoint('List vendor orders')]
    #[Response('{"data":[{"id":1,"order_number":"VORD-001","status":"pending"}],"meta":{"current_page":1}}', 200)]
    public function index(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN, 'Vendor profile required.');

        $orders = VendorOrder::query()
            ->where('vendor_profile_id', $vendorProfile->getKey())
            ->with(['items.product'])
            ->latest()
            ->paginate();

        return VendorOrderData::collect($orders, PaginatedDataCollection::class);
    }

    #[Authenticated]
    #[Endpoint('Get vendor order details')]
    #[Response('{"data":{"id":1,"order_number":"VORD-001","status":"pending","items":[]}}', 200)]
    public function show(VendorOrder $vendorOrder, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN, 'Vendor profile required.');
        abort_unless($vendorOrder->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $vendorOrder->load(['items.product', 'vendorProfile']);

        return VendorOrderData::fromModel($vendorOrder);
    }

    #[Authenticated]
    #[Endpoint('Accept vendor order')]
    #[Response('{"message":"Order accepted.","data":{"status":"packaging"}}', 200)]
    public function accept(VendorOrder $vendorOrder, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN, 'Vendor profile required.');
        abort_unless($vendorOrder->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);
        abort_unless($vendorOrder->isPending, HttpResponse::HTTP_UNPROCESSABLE_ENTITY, 'Order cannot be accepted.');

        $vendorOrder->update(['status' => VendorOrderStatus::Packaging]);
        $freshVendorOrder = $vendorOrder->fresh(['customer', 'items.product', 'vendorProfile']);
        $freshVendorOrder->customer?->notify(new VendorOrderStatusChanged($freshVendorOrder));

        return response()->json([
            'message' => 'Order accepted.',
            'data' => ['status' => $freshVendorOrder->status],
        ]);
    }

    #[Authenticated]
    #[Endpoint('Reject vendor order')]
    #[Response('{"message":"Order rejected.","data":{"status":"rejected"}}', 200)]
    public function reject(VendorOrder $vendorOrder, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN, 'Vendor profile required.');
        abort_unless($vendorOrder->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);
        abort_unless($vendorOrder->isPending, HttpResponse::HTTP_UNPROCESSABLE_ENTITY, 'Order cannot be rejected.');

        $vendorOrder->update(['status' => VendorOrderStatus::Rejected]);
        $freshVendorOrder = $vendorOrder->fresh(['customer', 'items.product', 'vendorProfile']);
        $freshVendorOrder->customer?->notify(new VendorOrderStatusChanged($freshVendorOrder));

        return response()->json([
            'message' => 'Order rejected.',
            'data' => ['status' => $freshVendorOrder->status],
        ]);
    }
}
