<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

it('has no remaining legacy endpoint middleware on api routes', function (): void {
    $routeKeys = collect(Route::getRoutes()->getRoutes())
        ->flatMap(fn ($route): array => $route->gatherMiddleware())
        ->filter(fn (string $middleware): bool => str_starts_with($middleware, 'legacy-endpoint:'))
        ->values()
        ->all();

    expect($routeKeys)->toBeEmpty();
});

it('keeps the legacy endpoint migration map empty once aliases are removed', function (): void {
    expect(config('api_migration.legacy_endpoints'))
        ->toBeArray()
        ->toBeEmpty();
});
