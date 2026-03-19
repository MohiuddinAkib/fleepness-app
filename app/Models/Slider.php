<?php

declare(strict_types=1);

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Database\Factories\SliderFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Concerns\LogsModelActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slider extends Model implements HasMedia
{
    /** @use HasFactory<SliderFactory> */
    use HasFactory, InteractsWithMedia, LogsModelActivity;

    /** @var list<string> */
    protected $fillable = [
        'category_id',
        'tag_id',
        'url',
        'is_active',
        'sort_order',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    #[Scope]
    public function active(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /** @return BelongsTo<Category, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** @return BelongsTo<Tag, $this> */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}
