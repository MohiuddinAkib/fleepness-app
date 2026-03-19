<?php

declare(strict_types=1);

use App\Models\Tag;
use App\Models\Category;

it('lists active root categories', function (): void {
    Category::factory()->count(3)->create();
    Category::factory()->count(2)->inactive()->create();

    $response = $this->getJson('/api/categories');

    $response->assertOk()->assertJsonCount(3, 'data');
});

it('only returns root categories', function (): void {
    $parent = Category::factory()->create();
    Category::factory()->count(2)->withParent($parent->getKey())->create();

    $response = $this->getJson('/api/categories');

    $response->assertOk()->assertJsonCount(1, 'data');
});

it('shows a single category', function (): void {
    $parent = Category::factory()->create(['name' => 'Parent Category']);
    $category = Category::factory()->withParent($parent->getKey())->create(['name' => 'Electronics']);
    Category::factory()->withParent($category->getKey())->create(['name' => 'Phones']);

    $response = $this->getJson("/api/categories/{$category->getKey()}");
    $childrenPayload = data_get($response->json(), 'data.children.data', data_get($response->json(), 'data.children', []));

    $response->assertOk()
        ->assertJsonPath('data.name', 'Electronics')
        ->assertJsonPath('data.parent.id', $parent->getKey())
        ->assertJsonPath('data.parent.name', 'Parent Category');

    expect(collect($childrenPayload)->first()['name'] ?? null)->toBe('Phones');
});

it('lists tags', function (): void {
    Tag::factory()->count(5)->create();

    $response = $this->getJson('/api/tags');

    $response->assertOk()->assertJsonCount(5, 'data');
});
