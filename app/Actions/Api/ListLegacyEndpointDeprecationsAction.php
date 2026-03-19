<?php

declare(strict_types=1);

namespace App\Actions\Api;

use Illuminate\Support\Facades\Route;
use App\Data\Migration\LegacyEndpointDeprecationData;

class ListLegacyEndpointDeprecationsAction
{
    /**
     * @return list<LegacyEndpointDeprecationData>
     */
    public function execute(): array
    {
        $metadata = config('api_migration.legacy_endpoints', []);

        $methodsByKey = collect(Route::getRoutes()->getRoutes())
            ->flatMap(function ($route): array {
                $keys = collect($route->gatherMiddleware())
                    ->filter(fn (string $middleware): bool => str_starts_with($middleware, 'legacy-endpoint:'))
                    ->map(fn (string $middleware): string => str($middleware)->after('legacy-endpoint:')->toString())
                    ->all();

                return collect($keys)
                    ->mapWithKeys(fn (string $key): array => [
                        $key => array_values(array_diff($route->methods(), ['HEAD'])),
                    ])
                    ->all();
            })
            ->map(fn (array $methods): array => array_values(array_unique($methods)))
            ->all();

        return collect($metadata)
            ->map(function (array $entry, string $key) use ($methodsByKey): LegacyEndpointDeprecationData {
                return new LegacyEndpointDeprecationData(
                    key: $key,
                    legacyPath: (string) $entry['path'],
                    methods: $methodsByKey[$key] ?? ['GET'],
                    replacements: array_values($entry['replacements'] ?? []),
                );
            })
            ->sortBy('legacyPath')
            ->values()
            ->all();
    }
}
