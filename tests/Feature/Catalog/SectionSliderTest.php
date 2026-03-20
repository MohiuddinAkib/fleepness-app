<?php

declare(strict_types=1);

use App\Models\Tag;
use App\Models\Slider;
use App\Models\Section;
use App\Models\Category;
use App\Enums\SectionType;
use App\Models\SectionItem;
use Illuminate\Http\UploadedFile;

it('lists visible sections', function (): void {
    $section = Section::factory()->create([
        'name' => 'Featured',
        'title' => 'Featured Deals',
        'description' => 'Curated offers',
        'type' => SectionType::ScrollableProduct,
        'placement_type' => 'homepage',
        'sort_order' => 2,
        'category_sort_order' => 5,
    ]);
    $item = SectionItem::factory()->for($section)->create([
        'title' => 'Section Item',
        'description' => 'Section item description',
        'sort_order' => 7,
        'is_visible' => true,
    ]);
    $item->load('tag');
    $section
        ->addMedia(UploadedFile::fake()->image('background.jpg'))
        ->toMediaCollection('background_image');
    $section
        ->addMedia(UploadedFile::fake()->image('banner.jpg'))
        ->toMediaCollection('banner_image');
    $item
        ->addMedia(UploadedFile::fake()->image('item-image.jpg'))
        ->toMediaCollection('image');
    Section::factory()->count(2)->create();
    Section::factory()->count(2)->create(['is_visible' => false]);

    $response = $this->getJson('/api/v1/sections');
    $sectionPayload = collect($response->json('data'))->firstWhere('id', $section->getKey());

    $response->assertOk()
        ->assertJsonCount(3, 'data');

    expect($sectionPayload)
        ->not->toBeNull()
        ->and(data_get($sectionPayload, 'section_name'))->toBe($section->name)
        ->and(data_get($sectionPayload, 'section_title'))->toBe($section->title)
        ->and(data_get($sectionPayload, 'section_type'))->toBe($section->type->value)
        ->and(data_get($sectionPayload, 'bio'))->toBe($section->description)
        ->and(data_get($sectionPayload, 'placement_type'))->toBe($section->placement_type)
        ->and(data_get($sectionPayload, 'index'))->toBe($section->sort_order)
        ->and(data_get($sectionPayload, 'cat_index'))->toBe($section->category_sort_order)
        ->and(data_get($sectionPayload, 'visibility'))->toBeTrue()
        ->and(data_get($sectionPayload, 'show_products'))->toBeTrue()
        ->and(data_get($sectionPayload, 'background_image'))->toBe($section->getFirstMediaUrl('background_image'))
        ->and(data_get($sectionPayload, 'banner_image'))->toBe($section->getFirstMediaUrl('banner_image'))
        ->and(data_get($sectionPayload, 'tag.id'))->toBe($item->tag?->getKey())
        ->and(data_get($sectionPayload, 'tag.name'))->toBe($item->tag?->name)
        ->and(data_get($sectionPayload, 'items.0.image'))->toBe($item->getFirstMediaUrl('image'))
        ->and(data_get($sectionPayload, 'items.0.bio'))->toBe($item->description)
        ->and(data_get($sectionPayload, 'items.0.index'))->toBe($item->sort_order)
        ->and(data_get($sectionPayload, 'items.0.tag.id'))->toBe($item->tag?->getKey())
        ->and(data_get($sectionPayload, 'items.0.tag.name'))->toBe($item->tag?->name);
});

it('lists active sliders', function (): void {
    $category = Category::factory()->create(['name' => 'Promo Category']);
    $tag = Tag::factory()->create(['name' => 'Promo Tag']);
    $slider = Slider::factory()->create([
        'category_id' => $category->getKey(),
        'tag_id' => $tag->getKey(),
        'url' => 'https://example.com/promo',
    ]);
    $slider
        ->addMedia(UploadedFile::fake()->image('slider-image.jpg'))
        ->toMediaCollection('image');
    Slider::factory()->count(3)->create();
    Slider::factory()->count(1)->create(['is_active' => false]);

    $response = $this->getJson('/api/v1/sliders');
    $sliderPayload = collect($response->json('data'))->firstWhere('id', $slider->getKey());

    $response->assertOk()
        ->assertJsonCount(4, 'data');

    expect($sliderPayload)
        ->not->toBeNull()
        ->and(data_get($sliderPayload, 'photo'))->toBe($slider->getFirstMediaUrl('image'))
        ->and(data_get($sliderPayload, 'image_url'))->toBe($slider->getFirstMediaUrl('image'))
        ->and(data_get($sliderPayload, 'category.id'))->toBe($category->getKey())
        ->and(data_get($sliderPayload, 'category.name'))->toBe($category->name)
        ->and(data_get($sliderPayload, 'tag.id'))->toBe($tag->getKey())
        ->and(data_get($sliderPayload, 'tag.name'))->toBe($tag->name)
        ->and(data_get($sliderPayload, 'url'))->toBe($slider->url);
});
