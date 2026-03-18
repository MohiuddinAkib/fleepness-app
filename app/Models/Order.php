<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'user_id',
        'delivery_option_id',
        'order_number',
        'is_multi_vendor',
        'vendor_count',
        'product_total',
        'delivery_fee',
        'platform_fee',
        'vat',
        'commission',
        'grand_total',
        'balance',
        'is_completed',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_multi_vendor' => 'boolean',
            'is_completed' => 'boolean',
            'product_total' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'platform_fee' => 'decimal:2',
            'vat' => 'decimal:2',
            'commission' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'balance' => 'decimal:2',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<DeliveryOption, $this> */
    public function deliveryOption(): BelongsTo
    {
        return $this->belongsTo(DeliveryOption::class);
    }

    /** @return HasMany<VendorOrder, $this> */
    public function vendorOrders(): HasMany
    {
        return $this->hasMany(VendorOrder::class);
    }
}
