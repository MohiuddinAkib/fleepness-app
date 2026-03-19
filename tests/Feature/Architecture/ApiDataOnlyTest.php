<?php

declare(strict_types=1);

it('does not keep the legacy http resources layer', function (): void {
    expect(glob(app_path('Http/Resources/*.php')))->toBeEmpty();
});
