<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Product;

class ApproveProductAction
{
    public function execute(Product $product, bool $isApproved): Product
    {
        $product->update([
            'is_approved' => $isApproved,
        ]);

        return $product->fresh();
    }
}
