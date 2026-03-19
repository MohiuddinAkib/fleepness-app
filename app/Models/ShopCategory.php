<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\LogsModelActivity;
use Database\Factories\ShopCategoryFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShopCategory extends Model
{
    /** @use HasFactory<ShopCategoryFactory> */
    use HasFactory, LogsModelActivity;

    /** @var list<string> */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /** @return HasMany<VendorProfile, $this> */
    public function vendorProfiles(): HasMany
    {
        return $this->hasMany(VendorProfile::class);
    }
}
