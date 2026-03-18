<?php

declare(strict_types=1);

namespace App\Data\Dto;

use App\Models\Product;
use App\Constants\GateNames;
use Spatie\LaravelData\Data;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class AddLivestreamProductData extends Data
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

    // public static function authorize(): Response|bool
    // {
    //     $livestream = request()->route('livestream');

    //     return $livestream;
    // return Gate::authorize(GateNames::ADD_LIVESTREAM_PRODUCTS->value, $livestream);
    // }
}
