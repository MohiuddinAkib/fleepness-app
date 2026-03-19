<?php

declare(strict_types=1);

use Tests\TestCase;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;

uses(TestCase::class);

it('uses carbon immutable through laravels date factory', function (): void {
    expect(Date::now())->toBeInstanceOf(CarbonImmutable::class)
        ->and(now())->toBeInstanceOf(CarbonImmutable::class)
        ->and(today())->toBeInstanceOf(CarbonImmutable::class);
});
