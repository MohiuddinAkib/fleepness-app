<?php

declare(strict_types=1);

it('generates and serves API documentation artifacts', function (): void {
    $this->artisan('scribe:generate', ['--no-interaction' => true])->assertExitCode(0);

    expect(file_exists(storage_path('app/private/scribe/collection.json')))->toBeTrue();
    expect(file_exists(storage_path('app/private/scribe/openapi.yaml')))->toBeTrue();
    expect(file_get_contents(storage_path('app/private/scribe/openapi.yaml')))
        ->toContain('/api/v1/me/vendors/followers')
        ->toContain('/api/v1/me/summaries')
        ->toContain('/api/v1/me/notifications')
        ->toContain('/api/v1/me/short-videos/saved')
        ->toContain('/api/v1/me/livestreams/liked')
        ->toContain('/api/v1/me/followings')
        ->toContain('/api/v1/me/vendors')
        ->toContain('/api/v1/me/balances')
        ->toContain('/api/v1/vendor-applications/status')
        ->toContain('/api/v1/deprecations/legacy-endpoints')
        ->toContain('similar_to_vendor_id')
        ->toContain('/similarvendors/{vendor}')
        ->toContain('in-price-range endpoint')
        ->toContain('in-price-category endpoint');

    $this->get('/docs')->assertOk();
    $this->get('/docs.postman')->assertOk();
    $this->get('/docs.openapi')->assertOk();
});
