<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Support\Str;
use App\Enums\TransactionType;
use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\LogsModelActivity;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    /** @use HasFactory<TransactionFactory> */
    use HasFactory, LogsModelActivity;

    /** @var list<string> */
    protected $fillable = [
        'user_id',
        'payment_method_id',
        'reference',
        'amount',
        'type',
        'status',
        'note',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'type' => TransactionType::class,
            'status' => TransactionStatus::class,
            'amount' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Transaction $transaction): void {
            $transaction->reference ??= (string) Str::ulid();
        });
    }

    public function isPending(): Attribute
    {
        return Attribute::get(fn (): bool => $this->status->isPending());
    }

    public function isApproved(): Attribute
    {
        return Attribute::get(fn (): bool => $this->status->isApproved());
    }

    public function isWithdrawal(): Attribute
    {
        return Attribute::get(fn (): bool => $this->type->isWithdrawal());
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<PaymentMethod, $this> */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
