<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\VendorOrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Concerns\LogsModelActivity;
use Database\Factories\VendorOrderFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorOrder extends Model
{
    /** @use HasFactory<VendorOrderFactory> */
    use HasFactory, LogsModelActivity;

    /** @var list<string> */
    protected $fillable = [
        'order_id',
        'vendor_profile_id',
        'customer_id',
        'order_number',
        'status',
        'status_note',
        'product_total',
        'commission',
        'vat',
        'delivery_fee',
        'balance',
        'is_rider_assigned',
        'is_delayed',
        'packaging_started_at',
        'expected_delivery_at',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'status' => VendorOrderStatus::class,
            'is_rider_assigned' => 'boolean',
            'is_delayed' => 'boolean',
            'product_total' => 'decimal:2',
            'commission' => 'decimal:2',
            'vat' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'balance' => 'decimal:2',
            'packaging_started_at' => 'datetime',
            'expected_delivery_at' => 'datetime',
        ];
    }

    /** @param Builder<VendorOrder> $query */
    #[Scope]
    public function delayed(Builder $query): void
    {
        $query
            ->whereNotIn('status', [
                VendorOrderStatus::Delivered,
                VendorOrderStatus::Rejected,
            ])
            ->whereNotNull('expected_delivery_at')
            ->where('expected_delivery_at', '<', now())
            ->where('is_delayed', false);
    }

    public function isPending(): Attribute
    {
        return Attribute::get(fn (): bool => $this->status->isPending());
    }

    /** @return BelongsTo<Order, $this> */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** @return BelongsTo<VendorProfile, $this> */
    public function vendorProfile(): BelongsTo
    {
        return $this->belongsTo(VendorProfile::class);
    }

    /** @return BelongsTo<User, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /** @return HasMany<VendorOrderItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(VendorOrderItem::class);
    }
}
