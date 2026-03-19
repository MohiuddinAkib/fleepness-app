<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Support\Str;
use App\Enums\ProductStatus;
use Spatie\MediaLibrary\HasMedia;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Concerns\LogsModelActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model implements HasMedia
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, InteractsWithMedia, LogsModelActivity, SoftDeletes;

    /** @var list<string> */
    protected $fillable = [
        'vendor_profile_id',
        'category_id',
        'size_template_id',
        'name',
        'slug',
        'sku',
        'quantity',
        'order_count',
        'selling_price',
        'discount_price',
        'short_description',
        'description',
        'status',
        'is_approved',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'status' => ProductStatus::class,
            'is_approved' => 'boolean',
            'selling_price' => 'decimal:2',
            'discount_price' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product): void {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name).'-'.Str::lower(Str::random(6));
            }
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }

    public function isActive(): Attribute
    {
        return Attribute::get(fn (): bool => $this->status->isActive());
    }

    #[Scope]
    public function active(Builder $query): void
    {
        $query->where('status', ProductStatus::Active);
    }

    #[Scope]
    public function approved(Builder $query): void
    {
        $query->where('is_approved', true);
    }

    /** @return BelongsTo<VendorProfile, $this> */
    public function vendorProfile(): BelongsTo
    {
        return $this->belongsTo(VendorProfile::class);
    }

    /** @return BelongsTo<Category, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** @return BelongsTo<SizeTemplate, $this> */
    public function sizeTemplate(): BelongsTo
    {
        return $this->belongsTo(SizeTemplate::class);
    }

    /** @return HasMany<ProductVariant, $this> */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /** @return HasMany<ProductReview, $this> */
    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    /** @return BelongsToMany<Tag, $this> */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }
}
