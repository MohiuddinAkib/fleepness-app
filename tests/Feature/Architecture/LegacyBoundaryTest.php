<?php

declare(strict_types=1);

it('keeps the top-level controller namespace on an explicit allowlist', function (): void {
    $controllers = collect(glob(app_path('Http/Controllers/*.php')) ?: [])
        ->map(fn (string $path): string => basename($path))
        ->reject(fn (string $path): bool => 'Controller.php' === $path)
        ->values()
        ->all();

    expect($controllers)->toBeEmpty();
});

it('keeps compatibility-only controllers referenced from the dedicated legacy route file', function (): void {
    expect(file_get_contents(base_path('routes/api/legacy.php')))
        ->toContain('CompatibilityController::class')
        ->toContain('App\Http\Controllers\Legacy\NotificationController');

    expect(file_get_contents(base_path('routes/api.php')))
        ->not->toContain('CompatibilityController::class')
        ->not->toContain('App\Http\Controllers\Legacy\NotificationController');

    expect(file_get_contents(base_path('routes/api/buyer.php')))
        ->not->toContain('CompatibilityController::class')
        ->not->toContain('App\Http\Controllers\Legacy\NotificationController');

    expect(file_get_contents(base_path('routes/api/vendor.php')))
        ->not->toContain('CompatibilityController::class')
        ->not->toContain('App\Http\Controllers\Legacy\NotificationController');
});
