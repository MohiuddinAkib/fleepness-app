<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\HttpClientServiceProvider;
use App\Providers\LivestreamServiceProvider;
use App\Providers\Filament\AdminPanelProvider;

return [
    AppServiceProvider::class,
    AuthServiceProvider::class,
    AdminPanelProvider::class,
    HttpClientServiceProvider::class,
    LivestreamServiceProvider::class,
];
