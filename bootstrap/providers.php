<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\HttpClientServiceProvider;
use App\Providers\LivestreamServiceProvider;
use App\Providers\Filament\AdminPanelProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    AuthServiceProvider::class,
    HttpClientServiceProvider::class,
    LivestreamServiceProvider::class,
];
