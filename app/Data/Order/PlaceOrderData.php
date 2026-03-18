<?php

declare(strict_types=1);

namespace App\Data\Order;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class PlaceOrderData extends Data
{
    public function __construct(
        public readonly int $deliveryOptionId,
        public readonly ?int $addressId,
    ) {}

    /** @return array<string, mixed> */
    public static function rules(): array
    {
        return [
            'delivery_option_id' => ['required', 'integer', 'exists:delivery_options,id'],
            'address_id' => ['nullable', 'integer', 'exists:addresses,id'],
        ];
    }
}
