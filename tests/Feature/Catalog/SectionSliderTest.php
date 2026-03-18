<?php

declare(strict_types=1);

use App\Models\Slider;
use App\Models\Section;

it('lists visible sections', function (): void {
    Section::factory()->count(3)->create();
    Section::factory()->count(2)->create(['is_visible' => false]);

    $response = $this->getJson('/api/sections');

    $response->assertOk()->assertJsonCount(3, 'data');
});

it('lists active sliders', function (): void {
    Slider::factory()->count(4)->create();
    Slider::factory()->count(1)->create(['is_active' => false]);

    $response = $this->getJson('/api/sliders');

    $response->assertOk()->assertJsonCount(4, 'data');
});
