<?php

declare(strict_types=1);

use App\Models\Product;
use App\Models\VendorProfile;

it('returns data-driven search results for products and vendors', function (): void {
    Product::factory()->create(['name' => 'Flash Tee']);
    VendorProfile::factory()->approved()->create(['shop_name' => 'Flash Store']);

    $this->getJson('/api/search?q=flash')
        ->assertOk()
        ->assertJsonPath('data.products.0.name', 'Flash Tee')
        ->assertJsonPath('data.vendors.0.shop_name', 'Flash Store');
});

it('returns empty search result groups for a blank query', function (): void {
    $this->getJson('/api/search?q=')
        ->assertOk()
        ->assertJsonPath('data.products', [])
        ->assertJsonPath('data.vendors', []);
});
