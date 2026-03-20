<?php

declare(strict_types=1);

namespace App\Data\Response\Me;

use Spatie\LaravelData\Data;
use App\Data\Me\AccountSummaryData;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Attributes\MapOutputName;

#[MapOutputName(SnakeCaseMapper::class)]
class AccountSummaryResponseData extends Data
{
    public function __construct(
        public readonly AccountSummaryData $data,
    ) {}
}
