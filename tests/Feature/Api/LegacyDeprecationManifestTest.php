<?php

declare(strict_types=1);

it('returns the versioned legacy deprecation manifest', function (): void {
    $this->getJson('/api/v1/deprecations/legacy-endpoints')
        ->assertOk()
        ->assertJsonPath('message', 'Legacy endpoint deprecations retrieved.')
        ->assertJsonPath('data', []);
});
