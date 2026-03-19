<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\VendorStatus;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\InteractsWithMedia;
use Database\Factories\VendorProfileFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorProfile extends Model implements HasMedia
{
    /** @use HasFactory<VendorProfileFactory> */
    use HasFactory, InteractsWithMedia;

    /** @var list<string> */
    protected $fillable = [
        'user_id',
        'shop_category_id',
        'shop_name',
        'description',
        'pickup_location',
        'balance',
        'total_sales',
        'withdrawn_amount',
        'order_count',
        'status',
        'status_note',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'status' => VendorStatus::class,
            'balance' => 'decimal:2',
            'total_sales' => 'decimal:2',
            'withdrawn_amount' => 'decimal:2',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('banner_image')->singleFile();
        $this->addMediaCollection('cover_image')->singleFile();
    }

    public function isApproved(): Attribute
    {
        return Attribute::get(fn (): bool => $this->status->isApproved());
    }

    #[Scope]
    public function approved(Builder $query): void
    {
        $query->where('status', VendorStatus::Approved);
    }

    public function isPending(): Attribute
    {
        return Attribute::get(fn (): bool => $this->status->isPending());
    }

    public function isRejected(): Attribute
    {
        return Attribute::get(fn (): bool => $this->status->isRejected());
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<ShopCategory, $this> */
    public function shopCategory(): BelongsTo
    {
        return $this->belongsTo(ShopCategory::class);
    }

    /** @return HasMany<Product, $this> */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /** @return HasMany<ShortVideo, $this> */
    public function shortVideos(): HasMany
    {
        return $this->hasMany(ShortVideo::class);
    }

    /** @return HasMany<VendorFollower, $this> */
    public function followers(): HasMany
    {
        return $this->hasMany(VendorFollower::class);
    }

    /** @return HasMany<VendorReview, $this> */
    public function reviews(): HasMany
    {
        return $this->hasMany(VendorReview::class);
    }
}
