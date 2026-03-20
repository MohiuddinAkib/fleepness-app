<?php

declare(strict_types=1);

it('removes legacy route middleware from every route file', function (): void {
    expect(file_get_contents(base_path('routes/api.php')))->not->toContain('legacy-endpoint:');
    expect(file_get_contents(base_path('routes/api/buyer.php')))->not->toContain('legacy-endpoint:');
    expect(file_get_contents(base_path('routes/api/vendor.php')))->not->toContain('legacy-endpoint:');
    expect(file_get_contents(base_path('routes/api/legacy.php')))->not->toContain('legacy-endpoint:');
});
