<?php

declare(strict_types=1);

use App\Models\DeliveryOption;

it('lists active delivery options', function (): void {
    DeliveryOption::factory()->count(3)->create();
    DeliveryOption::factory()->count(1)->create(['is_active' => false]);

    $this->getJson('/api/v1/delivery-options')
        ->assertOk()
        ->assertJsonCount(3, 'data');
});
