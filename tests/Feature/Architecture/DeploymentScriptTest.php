<?php

declare(strict_types=1);

it('defines an envoy deployment story for the current infrastructure', function (): void {
    $envoy = file_get_contents(base_path('Envoy.blade.php'));

    expect($envoy)
        ->not->toBeFalse()
        ->toContain("@story('deploy', ['on' => 'staging'])")
        ->toContain("@story('deploy-production', ['on' => 'production'])")
        ->toContain('/var/www/backend_v2')
        ->toContain('{{ $composerBinary }} install')
        ->toContain('{{ $npmBinary }} run build')
        ->toContain('{{ $artisan }} migrate --force')
        ->toContain('{{ $artisan }} queue:restart')
        ->toContain('{{ $artisan }} octane:reload')
        ->toContain('{{ $artisan }} reverb:restart');
});
