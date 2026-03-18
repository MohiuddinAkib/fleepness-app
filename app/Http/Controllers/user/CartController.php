<?php

namespace App\Http\Controllers\user;

use App\Models\Fee;
use App\Models\User;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use App\Models\DeliveryModel;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use App\Data\Dto\AddOrUpdateCartItemData;
use Illuminate\Container\Attributes\CurrentUser;

class CartController extends Controller
{
    public function addOrUpdate(AddOrUpdateCartItemData $data, #[CurrentUser] User $user): JsonResponse
    {
        $product = Product::findOrFail($data->productId);

        if ($data->quantity > $product->quantity) {
            return response()->json(['message' => 'Quantity exceeds available stock.'], 400);
        }

        $hasSizes = ProductSize::where('product_id', $data->productId)->exists();

        if ($hasSizes && blank($data->sizeId)) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => ['size_id' => ['Please select a size for this product.']],
            ], 422);
        }

        $cartItem = CartItem::updateOrCreate(
            [
                'user_id' => $user->getKey(),
                'product_id' => $data->productId,
                'size_id' => $data->sizeId,
            ],
            [
                'quantity' => $data->quantity,
                'selected' => true,
            ]
        );

        $cartItem->load(['product.images', 'size']);

        return response()->json([
            'message' => 'Cart item created or updated',
            'cart_item' => CartItemResource::make($cartItem),
        ]);
    }

    public function index(#[CurrentUser] User $user): JsonResponse
    {
        $cartItems = CartItem::with(['product.images', 'size'])
            ->where('user_id', $user->getKey())
            ->get();

        return response()->json([
            'cart_items' => CartItemResource::collection($cartItems),
        ]);
    }

    public function destroy(CartItem $cartItem, #[CurrentUser] User $user): JsonResponse
    {
        abort_unless($cartItem->user_id === $user->getKey(), 403, 'Unauthorized');

        $cartItem->delete();

        return response()->json(['message' => 'Item removed from cart']);
    }

    public function summary(Request $request, #[CurrentUser] User $user): JsonResponse
    {
        $deliveryModelId = $request->query('delivery_model_id', 1);
        $deliveryModel = DeliveryModel::find($deliveryModelId) ?? DeliveryModel::find(1);

        $selectedItems = CartItem::with('product')
            ->where('user_id', $user->getKey())
            ->where('selected', true)
            ->get();

        $itemTotal = $selectedItems->sum(function (CartItem $item): float {
            $price = $item->product->discount_price ?? $item->product->selling_price;

            return $price * $item->quantity;
        });

        $fee = Fee::query()->first();
        $platformFee = (float) ($fee?->platform_fee ?? 0);
        $vatFee = $fee ? round($itemTotal * ($fee->vat / 100), 2) : 0;
        $deliveryFee = (float) ($deliveryModel?->fee ?? 0);

        return response()->json([
            'item_total' => $itemTotal,
            'delivery_fee' => $deliveryFee,
            'platform_fee' => $platformFee,
            'vat_fee' => $vatFee,
            'grand_total' => $itemTotal + $platformFee + $vatFee + $deliveryFee,
        ]);
    }
}
