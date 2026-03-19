<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\HorizonServiceProvider;
use App\Providers\HttpClientServiceProvider;
use App\Providers\LivestreamServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\TypeScriptTransformerServiceProvider;

return [
    AppServiceProvider::class,
    AuthServiceProvider::class,
    AdminPanelProvider::class,
    HorizonServiceProvider::class,
    HttpClientServiceProvider::class,
    LivestreamServiceProvider::class,
    TypeScriptTransformerServiceProvider::class,
];
