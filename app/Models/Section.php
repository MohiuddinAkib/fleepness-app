<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SectionType;
use Spatie\MediaLibrary\HasMedia;
use Database\Factories\SectionFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model implements HasMedia
{
    /** @use HasFactory<SectionFactory> */
    use HasFactory, InteractsWithMedia;

    /** @var list<string> */
    protected $fillable = [
        'category_id',
        'name',
        'title',
        'type',
        'description',
        'placement_type',
        'sort_order',
        'category_sort_order',
        'is_visible',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'type' => SectionType::class,
            'is_visible' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('background_image')->singleFile();
        $this->addMediaCollection('banner_image')->singleFile();
    }

    /** @return HasMany<SectionItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(SectionItem::class)->orderBy('sort_order');
    }

    /** @return BelongsTo<Category, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
