<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\FeeFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\LogsModelActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fee extends Model
{
    /** @use HasFactory<FeeFactory> */
    use HasFactory, LogsModelActivity;

    /** @var list<string> */
    protected $fillable = [
        'vat',
        'platform_fee',
        'commission',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'vat' => 'decimal:2',
            'platform_fee' => 'decimal:2',
            'commission' => 'decimal:2',
        ];
    }
}
