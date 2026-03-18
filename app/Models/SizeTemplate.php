<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\SizeTemplateFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SizeTemplate extends Model
{
    /** @use HasFactory<SizeTemplateFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'vendor_profile_id',
        'name',
    ];

    /** @return BelongsTo<VendorProfile, $this> */
    public function vendorProfile(): BelongsTo
    {
        return $this->belongsTo(VendorProfile::class);
    }

    /** @return HasMany<SizeTemplateItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(SizeTemplateItem::class);
    }
}
