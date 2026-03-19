<?php

declare(strict_types=1);

it('generates and serves API documentation artifacts', function (): void {
    $this->artisan('scribe:generate', ['--no-interaction' => true])->assertExitCode(0);

    expect(file_exists(storage_path('app/private/scribe/collection.json')))->toBeTrue();
    expect(file_exists(storage_path('app/private/scribe/openapi.yaml')))->toBeTrue();
    expect(file_get_contents(storage_path('app/private/scribe/openapi.yaml')))
        ->toContain('/api/me/vendors/followers')
        ->toContain('/api/me/notifications')
        ->toContain('/api/me/short-videos/saved')
        ->toContain('/api/me/livestreams/liked')
        ->toContain('/api/me/followings')
        ->toContain('/api/me/vendors')
        ->toContain('/api/me/balances')
        ->toContain('/api/vendor-applications/status');

    $this->get('/docs')->assertOk();
    $this->get('/docs.postman')->assertOk();
    $this->get('/docs.openapi')->assertOk();
});
