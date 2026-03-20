<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

require __DIR__.'/api/canonical.php';

Route::prefix('v1')->group(function (): void {
    require __DIR__.'/api/canonical.php';
});
