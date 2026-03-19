<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\VendorOrder;
use App\Data\VendorOrderData;
use App\Enums\VendorOrderStatus;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Responsable;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class VendorOrderController extends Controller
{
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

    public function show(VendorOrder $vendorOrder, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN, 'Vendor profile required.');
        abort_unless($vendorOrder->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);

        $vendorOrder->load(['items.product', 'vendorProfile']);

        return VendorOrderData::fromModel($vendorOrder);
    }

    public function accept(VendorOrder $vendorOrder, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN, 'Vendor profile required.');
        abort_unless($vendorOrder->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);
        abort_unless($vendorOrder->is_pending, HttpResponse::HTTP_UNPROCESSABLE_ENTITY, 'Order cannot be accepted.');

        $vendorOrder->update(['status' => VendorOrderStatus::Packaging]);

        return response()->json([
            'message' => 'Order accepted.',
            'data' => ['status' => $vendorOrder->fresh()->status],
        ]);
    }

    public function reject(VendorOrder $vendorOrder, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $vendorProfile = $user->vendorProfile;
        abort_if(null === $vendorProfile, HttpResponse::HTTP_FORBIDDEN, 'Vendor profile required.');
        abort_unless($vendorOrder->vendorProfile()->is($vendorProfile), HttpResponse::HTTP_FORBIDDEN);
        abort_unless($vendorOrder->is_pending, HttpResponse::HTTP_UNPROCESSABLE_ENTITY, 'Order cannot be rejected.');

        $vendorOrder->update(['status' => VendorOrderStatus::Rejected]);

        return response()->json([
            'message' => 'Order rejected.',
            'data' => ['status' => $vendorOrder->fresh()->status],
        ]);
    }
}
