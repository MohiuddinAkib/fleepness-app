<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\DeliveryOptionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryOption extends Model
{
    /** @use HasFactory<DeliveryOptionFactory> */
    use HasFactory;

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
}
