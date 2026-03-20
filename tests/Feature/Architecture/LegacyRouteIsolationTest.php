<?php

declare(strict_types=1);

it('removes legacy route middleware from every route file', function (): void {
    expect(file_get_contents(base_path('routes/api.php')))->not->toContain('legacy-endpoint:');
    expect(file_get_contents(base_path('routes/api/buyer.php')))->not->toContain('legacy-endpoint:');
    expect(file_get_contents(base_path('routes/api/vendor.php')))->not->toContain('legacy-endpoint:');
    expect(file_get_contents(base_path('routes/api/canonical.php')))->not->toContain('legacy-endpoint:');
});

it('has no unversioned routes in api.php', function (): void {
    $content = file_get_contents(base_path('routes/api.php'));

    expect($content)->not->toContain("require __DIR__.'/api/canonical.php';\n\nRoute::prefix");
    expect($content)->toContain("Route::prefix('v1')");
});

it('has no legacy deprecation endpoint', function (): void {
    expect(file_get_contents(base_path('routes/api.php')))->not->toContain('deprecations/legacy-endpoints');
});
