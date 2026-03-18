<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\User;
use App\Models\CartItem;
use App\Data\CartItemData;
use App\Attributes\CurrentUser;
use App\Data\Cart\AddToCartData;
use Spatie\LaravelData\Optional;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Data\Cart\UpdateCartItemData;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class CartController extends Controller
{
    public function index(#[CurrentUser] User $user): JsonResponse
    {
        $items = CartItem::query()
            ->where('user_id', $user->getKey())
            ->with(['product', 'variant'])
            ->get();

        return Response::json([
            'data' => CartItemData::collect($items),
        ]);
    }

    public function store(AddToCartData $data, #[CurrentUser] User $user): JsonResponse
    {
        $cartItem = CartItem::query()->updateOrCreate(
            [
                'user_id' => $user->getKey(),
                'product_id' => $data->productId,
                'product_variant_id' => $data->productVariantId,
            ],
            [
                'quantity' => $data->quantity,
                'is_selected' => true,
            ]
        );

        $cartItem->load(['product', 'variant']);

        return Response::json([
            'message' => 'Item added to cart.',
            'data' => CartItemData::fromModel($cartItem),
        ], HttpResponse::HTTP_CREATED);
    }

    public function update(UpdateCartItemData $data, CartItem $cartItem, #[CurrentUser] User $user): JsonResponse
    {
        abort_unless($cartItem->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $updates = [];

        if (! $data->quantity instanceof Optional) {
            $updates['quantity'] = $data->quantity;
        }

        if (! $data->isSelected instanceof Optional) {
            $updates['is_selected'] = $data->isSelected;
        }

        if ([] !== $updates) {
            $cartItem->update($updates);
        }

        $cartItem->load(['product', 'variant']);

        return Response::json([
            'message' => 'Cart item updated.',
            'data' => CartItemData::fromModel($cartItem),
        ]);
    }

    public function destroy(CartItem $cartItem, #[CurrentUser] User $user): JsonResponse
    {
        abort_unless($cartItem->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $cartItem->delete();

        return Response::json(['message' => 'Item removed from cart.']);
    }

    public function summary(#[CurrentUser] User $user): JsonResponse
    {
        $items = CartItem::query()
            ->where('user_id', $user->getKey())
            ->where('is_selected', true)
            ->with('product')
            ->get();

        $productTotal = $items->sum(
            fn (CartItem $item) => (float) $item->product->selling_price * $item->quantity
        );

        return Response::json([
            'data' => [
                'item_count' => $items->count(),
                'product_total' => number_format($productTotal, 2, '.', ''),
            ],
        ]);
    }
}
