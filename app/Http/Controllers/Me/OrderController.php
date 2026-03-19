<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\Fee;
use App\Models\User;
use App\Models\Order;
use App\Data\OrderData;
use App\Models\CartItem;
use App\Models\VendorOrder;
use Illuminate\Support\Str;
use App\Models\DeliveryOption;
use App\Enums\VendorOrderStatus;
use Illuminate\Http\JsonResponse;
use App\Data\Order\PlaceOrderData;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\BodyParam;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Spatie\LaravelData\PaginatedDataCollection;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group('Orders', 'Customer order history and placement.')]
class OrderController extends Controller
{
    #[Authenticated]
    #[Endpoint('List own orders', 'Returns a paginated list of all orders placed by the authenticated user.')]
    #[Response('{"data":[{"id":1,"order_number":"ORD-001","grand_total":"250.00","is_completed":false}],"meta":{"current_page":1}}', 200)]
    public function index(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $orders = Order::query()
            ->where('user_id', $user->getKey())
            ->with(['vendorOrders.items.product'])
            ->latest()
            ->paginate();

        return OrderData::collect($orders, PaginatedDataCollection::class);
    }

    #[Authenticated]
    #[Endpoint('Get order details')]
    #[Response('{"data":{"id":1,"order_number":"ORD-001","grand_total":"250.00","vendor_orders":[]}}', 200)]
    public function show(Order $order, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        abort_unless($order->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $order->load(['vendorOrders.items.product', 'deliveryOption']);

        return OrderData::fromModel($order);
    }

    #[BodyParam('delivery_option_id', 'integer', required: true, example: 1)]
    #[BodyParam('address_id', 'integer', required: false, example: 1)]
    #[Endpoint('Place order', 'Places an order from the selected cart items. Creates separate vendor orders for each vendor. Cart items with is_selected=true are used.')]
    #[Response('{"message":"Order placed.","data":{"id":1,"order_number":"ORD-001","grand_total":"250.00"}}', 201)]
    public function store(PlaceOrderData $data, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $selectedItems = CartItem::query()
            ->where('user_id', $user->getKey())
            ->where('is_selected', true)
            ->with(['product.vendorProfile'])
            ->get();

        abort_if($selectedItems->isEmpty(), HttpResponse::HTTP_UNPROCESSABLE_ENTITY, 'Cart is empty.');

        $fee = Fee::query()->first();

        $deliveryOption = DeliveryOption::query()->findOrFail($data->deliveryOptionId);

        // Calculate totals
        $productTotal = $selectedItems->sum(
            fn (CartItem $item) => (float) $item->product->selling_price * $item->quantity
        );
        $deliveryFee = (float) $deliveryOption->fee;
        $vatRate = $fee ? (float) $fee->vat / 100 : 0;
        $commissionRate = $fee ? (float) $fee->commission / 100 : 0;
        $platformFeeAmount = $fee ? (float) $fee->platform_fee : 0;

        $vatAmount = round($productTotal * $vatRate, 2);
        $commissionAmount = round($productTotal * $commissionRate, 2);
        $grandTotal = round($productTotal + $deliveryFee + $vatAmount + $commissionAmount + $platformFeeAmount, 2);

        // Group items by vendor
        $byVendor = $selectedItems->groupBy(fn (CartItem $item) => $item->product->vendor_profile_id);
        $vendorCount = $byVendor->count();

        $order = DB::transaction(function () use (
            $user, $data, $byVendor, $vendorCount,
            $productTotal, $deliveryFee, $vatAmount, $commissionAmount, $platformFeeAmount, $grandTotal
        ): Order {
            $order = Order::query()->create([
                'user_id' => $user->getKey(),
                'delivery_option_id' => $data->deliveryOptionId,
                'order_number' => strtoupper(Str::random(12)),
                'is_multi_vendor' => 1 < $vendorCount,
                'vendor_count' => $vendorCount,
                'product_total' => $productTotal,
                'delivery_fee' => $deliveryFee,
                'platform_fee' => $platformFeeAmount,
                'vat' => $vatAmount,
                'commission' => $commissionAmount,
                'grand_total' => $grandTotal,
            ]);

            foreach ($byVendor as $vendorProfileId => $items) {
                $vendorProductTotal = $items->sum(
                    fn (CartItem $item) => (float) $item->product->selling_price * $item->quantity
                );
                $vendorCommission = round($vendorProductTotal * (isset($commissionAmount) ? $commissionAmount / max($productTotal, 1) : 0), 2);

                /** @var VendorOrder $vendorOrder */
                $vendorOrder = VendorOrder::query()->create([
                    'order_id' => $order->getKey(),
                    'vendor_profile_id' => $vendorProfileId,
                    'customer_id' => $user->getKey(),
                    'order_number' => strtoupper(Str::random(12)),
                    'status' => VendorOrderStatus::Pending,
                    'product_total' => $vendorProductTotal,
                    'commission' => $vendorCommission,
                    'vat' => 0,
                    'delivery_fee' => 0,
                    'balance' => $vendorProductTotal - $vendorCommission,
                ]);

                foreach ($items as $item) {
                    $unitPrice = (float) $item->product->selling_price;
                    $vendorOrder->items()->create([
                        'product_id' => $item->product->getKey(),
                        'product_variant_id' => $item->product_variant_id,
                        'quantity' => $item->quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => round($unitPrice * $item->quantity, 2),
                    ]);

                    // Decrement stock
                    $item->product->decrement('quantity', $item->quantity);
                }
            }

            // Remove selected cart items
            CartItem::query()
                ->where('user_id', $user->getKey())
                ->where('is_selected', true)
                ->delete();

            return $order;
        });

        $order->load(['vendorOrders.items.product']);

        return OrderData::fromModel($order)->additional(['message' => 'Order placed successfully.']);
    }
}
