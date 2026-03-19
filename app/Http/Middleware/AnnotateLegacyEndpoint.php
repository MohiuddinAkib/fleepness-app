<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AnnotateLegacyEndpoint
{
    public function handle(Request $request, Closure $next, string $key): Response
    {
        /** @var Response $response */
        $response = $next($request);
        $metadata = config('api_migration.legacy_endpoints')[$key] ?? null;

        if (! is_array($metadata)) {
            return $response;
        }

        $replacements = $metadata['replacements'] ?? [];

        $response->headers->set('X-Fleepness-Legacy-Endpoint', 'true');
        $response->headers->set('X-Fleepness-Migration-Key', $key);
        $response->headers->set('X-Fleepness-Legacy-Path', (string) ($metadata['path'] ?? $request->path()));
        $response->headers->set('X-Fleepness-Replacement-Endpoints', implode(',', $replacements));

        return $response;
    }
}
