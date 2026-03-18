<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use Lunar\Models\Order;
use Lunar\Models\OrderLine;
use Lunar\Models\ProductVariant;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Determine whether the user can permanently delete the model.
     */
    public function makePickupRequestToPathao(User $seller, Order $order): bool|Response
    {
        $vendor = $seller->vendors->first();

        /** @var OrderLine */
        $firstProductLine = $order->productLines->first();
        /** @var ProductVariant */
        $productVariant = $firstProductLine->purchasable;
        /** @var Product */
        $product = $productVariant->product;

        if ($product->vendor->isNot($vendor)) {
            return Response::denyAsNotFound();
        }

        if ($order->isDraft()) {
            return Response::deny('Order is not placed');
        }

        return true;
    }
}
