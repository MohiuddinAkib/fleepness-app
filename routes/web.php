<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::webhooks('livekit', 'livekit');

require __DIR__.'/auth.php';
