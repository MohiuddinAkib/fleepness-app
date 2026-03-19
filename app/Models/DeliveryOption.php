<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Concerns\LogsModelActivity;
use Database\Factories\DeliveryOptionFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryOption extends Model
{
    /** @use HasFactory<DeliveryOptionFactory> */
    use HasFactory, LogsModelActivity;

    /** @var list<string> */
    protected $fillable = [
        'name',
        'description',
        'estimated_minutes',
        'fee',
        'is_active',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'fee' => 'decimal:2',
        ];
    }

    #[Scope]
    public function active(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
