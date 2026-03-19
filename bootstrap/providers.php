<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\HttpClientServiceProvider;
use App\Providers\LivestreamServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\VendorPanelProvider;

return [
    AppServiceProvider::class,
    AuthServiceProvider::class,
    AdminPanelProvider::class,
    VendorPanelProvider::class,
    HttpClientServiceProvider::class,
    LivestreamServiceProvider::class,
];
