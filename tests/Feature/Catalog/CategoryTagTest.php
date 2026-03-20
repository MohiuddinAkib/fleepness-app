<?php

declare(strict_types=1);

use App\Models\Tag;
use App\Models\Category;

it('lists active root categories', function (): void {
    Category::factory()->count(3)->create();
    Category::factory()->count(2)->inactive()->create();

    $response = $this->getJson('/api/v1/categories');

    $response->assertOk()->assertJsonCount(3, 'data');
});

it('only returns root categories', function (): void {
    $parent = Category::factory()->create();
    Category::factory()->count(2)->withParent($parent->getKey())->create();

    $response = $this->getJson('/api/v1/categories');

    $response->assertOk()->assertJsonCount(1, 'data');
});

it('shows a single category', function (): void {
    $parent = Category::factory()->create(['name' => 'Parent Category']);
    $category = Category::factory()->withParent($parent->getKey())->create(['name' => 'Electronics']);
    Category::factory()->withParent($category->getKey())->create(['name' => 'Phones']);

    $response = $this->getJson("/api/v1/categories/{$category->getKey()}");
    $childrenPayload = data_get($response->json(), 'data.children.data', data_get($response->json(), 'data.children', []));

    $response->assertOk()
        ->assertJsonPath('data.name', 'Electronics')
        ->assertJsonPath('data.parent.id', $parent->getKey())
        ->assertJsonPath('data.parent.name', 'Parent Category');

    expect(collect($childrenPayload)->first()['name'] ?? null)->toBe('Phones');
});

it('lists tags', function (): void {
    Tag::factory()->count(5)->create();

    $response = $this->getJson('/api/v1/tags');

    $response->assertOk()->assertJsonCount(5, 'data');
});

it('shows a single tag', function (): void {
    $tag = Tag::factory()->create(['name' => 'Flash Sale', 'slug' => 'flash-sale']);

    $this->getJson("/api/v1/tags/{$tag->getKey()}")
        ->assertOk()
        ->assertJsonPath('data.id', $tag->getKey())
        ->assertJsonPath('data.name', 'Flash Sale')
        ->assertJsonPath('data.slug', 'flash-sale');
});
