<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Concerns\LogsModelActivity;
use Database\Factories\PaymentMethodFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    /** @use HasFactory<PaymentMethodFactory> */
    use HasFactory, LogsModelActivity;

    /** @var list<string> */
    protected $fillable = [
        'name',
        'icon_path',
        'is_active',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    #[Scope]
    public function active(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /** @return HasMany<UserPaymentAccount, $this> */
    public function userPaymentAccounts(): HasMany
    {
        return $this->hasMany(UserPaymentAccount::class);
    }
}
