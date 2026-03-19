<?php

declare(strict_types=1);

use App\Enums\ProductApprovalStatus;

it('exposes approval metadata for filament', function (): void {
    expect(ProductApprovalStatus::Approved->value)->toBe('1')
        ->and(ProductApprovalStatus::Approved->getLabel())->toBe('Approved')
        ->and(ProductApprovalStatus::Approved->getColor())->toBe('success')
        ->and(ProductApprovalStatus::Approved->getIcon())->toBe('heroicon-o-check-circle')
        ->and(ProductApprovalStatus::Pending->value)->toBe('0')
        ->and(ProductApprovalStatus::Pending->getLabel())->toBe('Pending')
        ->and(ProductApprovalStatus::Pending->getColor())->toBe('warning')
        ->and(ProductApprovalStatus::Pending->getIcon())->toBe('heroicon-o-clock');
});

it('maps booleans to approval states', function (): void {
    expect(ProductApprovalStatus::fromBoolean(true))->toBe(ProductApprovalStatus::Approved)
        ->and(ProductApprovalStatus::fromBoolean(false))->toBe(ProductApprovalStatus::Pending);
});
