<?php

declare(strict_types=1);

it('generates and serves API documentation artifacts', function (): void {
    $this->artisan('scribe:generate', ['--no-interaction' => true])->assertExitCode(0);

    expect(file_exists(storage_path('app/private/scribe/collection.json')))->toBeTrue();
    expect(file_exists(storage_path('app/private/scribe/openapi.yaml')))->toBeTrue();

    $this->get('/docs')->assertOk();
    $this->get('/docs.postman')->assertOk();
    $this->get('/docs.openapi')->assertOk();
});
