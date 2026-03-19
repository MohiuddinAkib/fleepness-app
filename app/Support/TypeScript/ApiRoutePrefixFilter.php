<?php

declare(strict_types=1);

namespace App\Support\TypeScript;

use Illuminate\Routing\Route;
use Spatie\LaravelTypeScriptTransformer\RouteFilters\RouteFilter;

class ApiRoutePrefixFilter implements RouteFilter
{
    public function hide(Route $route): bool
    {
        $uri = ltrim($route->uri(), '/');

        return ! str($uri)->startsWith([
            'api/',
            'broadcasting/auth',
        ]);
    }
}
