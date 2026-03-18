<?php

declare(strict_types=1);

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\ShortVideoFactory;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ShortVideo extends Model implements HasMedia
{
    /** @use HasFactory<ShortVideoFactory> */
    use HasFactory, InteractsWithMedia;

    /** @var list<string> */
    protected $fillable = [
        'vendor_profile_id',
        'title',
        'description',
        'likes_count',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'likes_count' => 'integer',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('video')->singleFile();
        $this->addMediaCollection('thumbnail')->singleFile();
    }

    /** @return BelongsTo<VendorProfile, $this> */
    public function vendorProfile(): BelongsTo
    {
        return $this->belongsTo(VendorProfile::class);
    }

    /** @return HasMany<ShortVideoComment, $this> */
    public function comments(): HasMany
    {
        return $this->hasMany(ShortVideoComment::class);
    }

    /** @return HasMany<ShortVideoLike, $this> */
    public function likes(): HasMany
    {
        return $this->hasMany(ShortVideoLike::class);
    }

    /** @return HasMany<ShortVideoSave, $this> */
    public function saves(): HasMany
    {
        return $this->hasMany(ShortVideoSave::class);
    }

    /** @return BelongsToMany<Product, $this> */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'short_video_products');
    }
}
