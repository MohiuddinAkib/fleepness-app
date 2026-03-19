<?php

declare(strict_types=1);

namespace App\Http\Controllers\Me;

use App\Models\Fee;
use App\Models\User;
use App\Models\CartItem;
use App\Data\CartItemData;
use Illuminate\Http\Request;
use App\Models\DeliveryOption;
use App\Data\Cart\AddToCartData;
use Spatie\LaravelData\Optional;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Data\Cart\UpdateCartItemData;
use Knuckles\Scribe\Attributes\Group;
use Spatie\LaravelData\DataCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\BodyParam;
use Illuminate\Contracts\Support\Responsable;
use Knuckles\Scribe\Attributes\Authenticated;
use Illuminate\Container\Attributes\CurrentUser;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

#[Group('Cart', 'Manage the authenticated user cart before checkout.')]
class CartController extends Controller
{
    #[Authenticated]
    #[Endpoint('List cart items')]
    #[Response('{"data":[{"id":1,"quantity":2,"is_selected":true,"product":{"id":15,"name":"Blue T-Shirt"}}]}', 200)]
    public function index(#[CurrentUser] User $user): JsonResponse|Responsable
    {
        $items = CartItem::query()
            ->where('user_id', $user->getKey())
            ->with(['product', 'variant'])
            ->get();

        return CartItemData::collect($items, DataCollection::class);
    }

    #[Authenticated]
    #[BodyParam('product_id', 'integer', required: true, example: 15)]
    #[BodyParam('product_variant_id', 'integer', required: false, example: 41)]
    #[BodyParam('quantity', 'integer', required: true, example: 2)]
    #[Endpoint('Add item to cart')]
    #[Response('{"message":"Item added to cart.","data":{"id":1,"quantity":2,"is_selected":true}}', 201)]
    public function store(AddToCartData $data, #[CurrentUser] User $user): JsonResponse|Responsable
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

        return CartItemData::fromModel($cartItem)->additional(['message' => 'Item added to cart.']);
    }

    #[Authenticated]
    #[BodyParam('quantity', 'integer', required: false, example: 3)]
    #[BodyParam('is_selected', 'boolean', required: false, example: true)]
    #[Endpoint('Update cart item')]
    #[Response('{"message":"Cart item updated.","data":{"id":1,"quantity":3,"is_selected":true}}', 200)]
    public function update(UpdateCartItemData $data, CartItem $cartItem, #[CurrentUser] User $user): JsonResponse|Responsable
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

        return CartItemData::fromModel($cartItem)->additional(['message' => 'Cart item updated.']);
    }

    #[Authenticated]
    #[Endpoint('Remove cart item')]
    #[Response('{"message":"Item removed from cart."}', 200)]
    public function destroy(CartItem $cartItem, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        abort_unless($cartItem->user()->is($user), HttpResponse::HTTP_FORBIDDEN);

        $cartItem->delete();

        return response()->json(['message' => 'Item removed from cart.']);
    }

    #[Authenticated]
    #[Endpoint('Get cart summary')]
    #[Response('{"data":{"item_count":2,"product_total":"250.00"}}', 200)]
    public function summary(Request $request, #[CurrentUser] User $user): JsonResponse|Responsable
    {
        $items = CartItem::query()
            ->where('user_id', $user->getKey())
            ->where('is_selected', true)
            ->with('product')
            ->get();

        $productTotal = $items->sum(fn (CartItem $item): float => $this->resolveUnitPrice($item) * $item->quantity);
        $fee = Fee::query()->first();
        $deliveryOption = DeliveryOption::query()->find($request->integer('delivery_option_id'))
            ?? DeliveryOption::query()->find($request->integer('delivery_model_id'))
            ?? DeliveryOption::query()->first();

        $deliveryFee = (float) ($deliveryOption?->fee ?? 0);
        $vatRate = (float) (($fee?->vat ?? 0) / 100);
        $commissionRate = (float) (($fee?->commission ?? 0) / 100);
        $platformFee = (float) ($fee?->platform_fee ?? 0);
        $vatFee = round($productTotal * $vatRate, 2);
        $commissionFee = round($productTotal * $commissionRate, 2);
        $grandTotal = round($productTotal + $deliveryFee + $vatFee + $commissionFee + $platformFee, 2);

        $payload = [
            'item_count' => $items->count(),
            'item_total' => number_format($productTotal, 2, '.', ''),
            'product_total' => number_format($productTotal, 2, '.', ''),
            'delivery_fee' => number_format($deliveryFee, 2, '.', ''),
            'platform_fee' => number_format($platformFee, 2, '.', ''),
            'vat_fee' => number_format($vatFee, 2, '.', ''),
            'commission_fee' => number_format($commissionFee, 2, '.', ''),
            'grand_total' => number_format($grandTotal, 2, '.', ''),
        ];

        return response()->json([
            ...$payload,
            'data' => $payload,
        ]);
    }

    private function resolveUnitPrice(CartItem $item): float
    {
        return (float) ($item->product->discount_price ?? $item->product->selling_price);
    }
}
