<?php

declare(strict_types=1);

it('returns the versioned legacy deprecation manifest', function (): void {
    $this->getJson('/api/v1/deprecations/legacy-endpoints')
        ->assertOk()
        ->assertJsonPath('message', 'Legacy endpoint deprecations retrieved.')
        ->assertJsonFragment([
            'key' => 'me.role',
            'legacy_path' => '/api/me/role',
        ])
        ->assertJsonFragment([
            'key' => 'seller.status',
            'legacy_path' => '/api/seller/status',
        ])
        ->assertJsonFragment([
            'key' => 'notifications.index',
            'legacy_path' => '/api/notifications',
        ]);
});
