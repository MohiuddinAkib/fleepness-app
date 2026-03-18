<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\User;
use App\Models\Order;
use App\Models\CartItem;
use App\Models\SellerOrder;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DeliveryModel;
use Illuminate\Http\Response;
use App\Models\SellerOrderItem;
use App\Data\Dto\StoreOrderData;
use App\Enums\SellerOrderStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\OrderResource;
use App\Http\Resources\SellerOrderResource;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Contracts\Database\Query\Builder;

class OrderController extends Controller
{
    public function store(StoreOrderData $data, #[CurrentUser()] User $user): JsonResponse
    {
        $fee = Fee::query()->first();

        $cartItems = CartItem::with(['product', 'size'])
            ->where('user_id', $user->getKey())
            ->where('selected', 1)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'No items selected in cart.'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $deliveryModel = DeliveryModel::query()->findOrFail($data->deliveryModelId);

        DB::beginTransaction();

        $userId = $user->getKey();

        try {
            $uniqueSellerCount = $cartItems->pluck('product.user_id')->unique()->count();
            $isMultiSeller = 1 < $uniqueSellerCount;

            $order = new Order;
            $order->user_id = $userId;
            $order->order_code = Str::orderId();
            $order->is_multi_seller = $isMultiSeller;
            $order->total_sellers = $uniqueSellerCount;
            $order->delivery_model_id = $deliveryModel->getKey();
            $order->delivery_fee = $deliveryModel->fee * $uniqueSellerCount;
            $order->product_cost = 0;
            $order->commission = 0;
            $order->platform_fee = 0;
            $order->vat = 0;
            $order->grand_total = 0;
            $order->save();

            $orderProductCost = 0;
            $sellerIndex = 1;

            $grouped = $cartItems->groupBy('product.user_id');

            foreach ($grouped as $sellerId => $sellerItems) {
                $sellerOrder = new SellerOrder;
                $sellerOrder->order_id = $order->id;
                $sellerOrder->seller_id = $sellerId;
                $sellerOrder->customer_id = $userId;
                $sellerOrder->status = SellerOrderStatus::Pending;
                $sellerOrder->product_cost = 0;
                $sellerOrder->commission = 0;
                $sellerOrder->vat = 0;
                $sellerOrder->delivery_fee = 0;
                $sellerOrder->balance = 0;
                $sellerOrder->rider_assigned = false;
                $sellerOrder->seller_order_code = $order->order_code.$sellerIndex++;
                $sellerOrder->save();

                $sellerTotal = 0;

                foreach ($sellerItems as $cartItem) {
                    $product = $cartItem->product;
                    $qty = $cartItem->quantity;

                    $price = ($product->discount_price && 0 < $product->discount_price)
                        ? $product->discount_price
                        : $product->selling_price;

                    $totalCost = $price * $qty;

                    $sItem = new SellerOrderItem;
                    $sItem->seller_order_id = $sellerOrder->id;
                    $sItem->product_id = $product->id;
                    $sItem->size = $cartItem->size_id ? $cartItem->size->size_name : null;
                    $sItem->quantity = $qty;
                    $sItem->total_cost = $totalCost;
                    $sItem->save();

                    $sellerTotal += $totalCost;

                    $product->decrement('quantity', $qty);
                }

                $sellerOrder->product_cost = $sellerTotal;
                $sellerOrder->commission = $sellerTotal * ($fee->commission / 100);
                $sellerOrder->vat = $sellerTotal * ($fee->vat / 100);
                $sellerOrder->delivery_fee = $order->delivery_fee / $uniqueSellerCount;
                $sellerOrder->save();

                $sellerOrder->notifySellerAboutNewOrderFromBuyer();

                $orderProductCost += $sellerTotal;
            }

            $order->product_cost = $orderProductCost;
            $order->commission = $orderProductCost * ($fee->commission / 100);
            $order->platform_fee = $fee->platform_fee;
            $order->vat = $orderProductCost * ($fee->vat / 100);
            $order->grand_total = $orderProductCost
                + (float) $order->delivery_fee
                + (float) $order->platform_fee
                + (float) $order->vat;
            $order->save();

            CartItem::query()->where('user_id', $userId)->where('selected', 1)->delete();

            DB::commit();

            $order->load([
                'sellerOrders.items.product.images',
                'sellerOrders.seller',
                'user.defaultAddress',
            ]);

            return response()->json([
                'message' => 'Order created successfully',
                'order' => OrderResource::make($order),
            ], Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function sellerOrders(#[CurrentUser()] User $seller, Request $request): JsonResponse
    {
        $query = SellerOrder::with([
            'items.product.images',
            'customer',
            'customer.defaultAddress',
        ])->where('seller_id', $seller->getKey());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $numericPart = preg_replace('/[^0-9]/', '', $search);

            $query->where(function ($q) use ($search, $numericPart): void {
                $q->where('seller_order_code', 'like', "%{$search}%")
                    ->orWhere('seller_order_code', 'like', "%{$numericPart}%")
                    ->orWhereHas('items.product', function ($q2) use ($search): void {
                        $q2->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('customer', function ($q3) use ($search): void {
                        $q3->where('phone_number', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->latest()->paginate(10);

        return response()->json([
            'message' => 'Seller orders retrieved successfully',
            'data' => SellerOrderResource::collection($orders),
        ]);
    }

    public function MyOrders(Request $request, #[CurrentUser()] User $user): JsonResponse
    {
        $status = $request->enum('status', SellerOrderStatus::class);

        $orders = Order::with([
            'sellerOrders.seller',
        ])
            ->where('user_id', $user->getKey())
            ->latest()
            ->when($status?->isDelivered())
            ->whereHas('sellerOrders', function (Builder $q): void {
                $q->where('status', SellerOrderStatus::Delivered);
            })
            ->when($status?->isActive())
            ->whereHas('sellerOrders', function (Builder $q): void {
                $q->whereNotIn('status', [SellerOrderStatus::Delivered, SellerOrderStatus::Rejected]);
            })
            ->when($status?->isActive())->whereHas('sellerOrders', function (Builder $q): void {
                $q->where('status', SellerOrderStatus::Rejected);
            })
            ->paginate(10);

        return response()->json([
            'message' => 'Orders retrieved successfully',
            'data' => OrderResource::collection($orders),
        ]);
    }

    public function MyStoreOrders(Request $request, #[CurrentUser()] User $seller): JsonResponse
    {
        $search = $request->query('search');
        $status = $request->enum('status', SellerOrderStatus::class);

        $orders = SellerOrder::query()
            ->where('seller_id', $seller->getKey())
            ->latest()
            ->when(filled($status))
            ->where('status', $status)
            ->when(filled($search))
            ->whereHas('customer', function (Builder $query) use ($search): void {
                $query->whereLike('name', "%{$search}%")
                    ->orWhereLike('phone_number', "%{$search}%");
            })
            ->paginate(10);

        return response()->json([
            'message' => 'Store orders retrieved successfully',
            'data' => SellerOrderResource::collection($orders),
        ]);
    }

    public function searchOrderById(Request $request, #[CurrentUser()] User $user): JsonResponse
    {
        $search = $request->query('order_code');

        abort_if(
            blank($search),
            response(['message' => 'Order ID is required.'], Response::HTTP_BAD_REQUEST)
        );

        $numericPart = preg_replace('/[^0-9]/', '', $search);

        $order = Order::with(['sellerOrders.items.product', 'sellerOrders.seller'])
            ->where('user_id', $user->getKey())
            ->where(function (Builder $query) use ($search, $numericPart): void {
                $query->whereLike('order_code', "%$search%")
                    ->orWhereLike('order_code', "%$numericPart%");
            })
            ->firstOrFail();

        return response()->json([
            'message' => 'Order retrieved successfully',
            'data' => OrderResource::make($order),
        ]);
    }

    public function sellerOrderDetail(SellerOrder $order, #[CurrentUser()] User $seller): JsonResponse
    {
        abort_unless($order->seller()->is($seller), Response::HTTP_NOT_FOUND, 'Seller order not found.');

        $order->load([
            'items.product.images',
            'customer',
            'customer.defaultAddress',
        ]);

        return response()->json([
            'message' => 'Seller order retrieved successfully',
            'data' => SellerOrderResource::make($order),
        ]);
    }

    public function myOrderDetail(Order $order, #[CurrentUser()] User $user): JsonResponse
    {
        abort_unless($order->user()->is($user), Response::HTTP_NOT_FOUND, 'Order not found.');

        $order->load([
            'sellerOrders.seller',
            'sellerOrders.items.product.images',
            'user.defaultAddress',
        ]);

        return response()->json([
            'message' => 'Order detail fetched successfully.',
            'order' => OrderResource::make($order),
        ]);
    }

    public function acceptSellerOrder(Request $request, SellerOrder $order, #[CurrentUser()] User $seller): JsonResponse
    {
        abort_unless($order->seller()->is($seller), Response::HTTP_NOT_FOUND, 'Seller order not found.');

        $order->status = SellerOrderStatus::Packaging;
        $order->status_message = $request->input('message', 'The order is in packaging');
        $order->delivery_start_time = now();

        $deliveryModel = $order->order->deliveryModel;

        if ($deliveryModel) {
            $order->delivery_end_time = now()->addMinutes($deliveryModel->minutes);
        }

        $order->save();
        $order->notifyBuyerAboutOrderStatus();

        return response()->json([
            'message' => 'Seller order accepted successfully',
            'data' => SellerOrderResource::make($order),
        ]);
    }

    public function rejectSellerOrder(Request $request, SellerOrder $order, #[CurrentUser()] User $seller): JsonResponse
    {
        abort_unless($order->seller()->is($seller), Response::HTTP_NOT_FOUND, 'Seller order not found.');

        $order->status = SellerOrderStatus::Rejected;
        $order->status_message = $request->input('message', 'The order is rejected by the seller');
        $order->save();
        $order->notifyBuyerAboutOrderStatus();

        return response()->json([
            'message' => 'You rejected the order successfully',
            'data' => SellerOrderResource::make($order),
        ]);
    }
}
