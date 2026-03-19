<?php

declare(strict_types=1);

it('keeps the top-level controller namespace on an explicit allowlist', function (): void {
    $controllers = collect(glob(app_path('Http/Controllers/*.php')) ?: [])
        ->map(fn (string $path): string => basename($path))
        ->reject(fn (string $path): bool => 'Controller.php' === $path)
        ->values()
        ->all();

    expect($controllers)->toEqualCanonicalizing([
        'AdminCategoryController.php',
        'CategoryController.php',
        'CompatibilityController.php',
        'DeliveryModelController.php',
        'FeeController.php',
        'NotificationController.php',
        'OrderController.php',
        'PaymentController.php',
        'ProfileController.php',
        'SectionController.php',
        'ShortsInteractionController.php',
        'SizeTemplateController.php',
        'SMSController.php',
        'TagController.php',
        'TransactionController.php',
        'UserController.php',
    ]);
});

it('keeps compatibility-only controllers referenced from the dedicated legacy route file', function (): void {
    expect(file_get_contents(base_path('routes/api/legacy.php')))
        ->toContain('CompatibilityController::class')
        ->toContain('NotificationController::class');

    expect(file_get_contents(base_path('routes/api.php')))
        ->not->toContain('CompatibilityController::class')
        ->not->toContain('NotificationController::class');

    expect(file_get_contents(base_path('routes/api/buyer.php')))
        ->not->toContain('CompatibilityController::class')
        ->not->toContain('NotificationController::class');

    expect(file_get_contents(base_path('routes/api/vendor.php')))
        ->not->toContain('CompatibilityController::class')
        ->not->toContain('NotificationController::class');
});
