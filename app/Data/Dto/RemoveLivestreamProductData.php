<?php

declare(strict_types=1);

namespace App\Data\Dto;

use App\Models\Product;
use Spatie\LaravelData\Data;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class RemoveLivestreamProductData extends Data
{
    public function __construct(
        public array $productIds
    ) {}

    public static function rules(): array
    {
        return [
            'product_ids.*' => [Rule::exists(Product::class, 'id')],
        ];
    }
}
