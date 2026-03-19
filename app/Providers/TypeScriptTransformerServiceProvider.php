<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use App\Support\TypeScript\ApiRoutePrefixFilter;
use Spatie\TypeScriptTransformer\Transformers\EnumTransformer;
use Spatie\TypeScriptTransformer\Writers\GlobalNamespaceWriter;
use Spatie\LaravelTypeScriptTransformer\RouteFilters\RouteFilter;
use Spatie\TypeScriptTransformer\TypeScriptTransformerConfigFactory;
use Spatie\LaravelTypeScriptTransformer\RouteFilters\NamedRouteFilter;
use Spatie\TypeScriptTransformer\Transformers\AttributedClassTransformer;
use Spatie\LaravelTypeScriptTransformer\RouteFilters\ControllerRouteFilter;
use Spatie\LaravelTypeScriptTransformer\TransformedProviders\LaravelRouteTransformedProvider;
use Spatie\LaravelTypeScriptTransformer\TransformedProviders\LaravelTypesTransformedProvider;
use Spatie\LaravelTypeScriptTransformer\LaravelData\LaravelDataTypeScriptTransformerExtension;
use Spatie\LaravelTypeScriptTransformer\TransformedProviders\LaravelControllerTransformedProvider;
use Spatie\LaravelTypeScriptTransformer\TypeScriptTransformerApplicationServiceProvider as BaseTypeScriptTransformerServiceProvider;

class TypeScriptTransformerServiceProvider extends BaseTypeScriptTransformerServiceProvider
{
    protected function configure(TypeScriptTransformerConfigFactory $config): void
    {
        $outputDirectory = storage_path('app/typescript');

        File::ensureDirectoryExists($outputDirectory);

        $config
            ->outputDirectory($outputDirectory)
            ->writer(new GlobalNamespaceWriter('types.d.ts'))
            ->extension(new LaravelDataTypeScriptTransformerExtension)
            ->provider(LaravelTypesTransformedProvider::class)
            ->provider(new LaravelControllerTransformedProvider(
                filters: $this->apiRouteFilters(),
                routeDirectories: $this->routeDirectories(),
            ))
            ->provider(new LaravelRouteTransformedProvider(
                filters: $this->apiRouteFilters(),
                path: 'helpers/route.ts',
                routeDirectories: $this->routeDirectories(),
                absoluteUrlsByDefault: false,
            ))
            ->transformer(AttributedClassTransformer::class)
            ->transformer(EnumTransformer::class)
            ->transformDirectories(
                app_path('Data'),
                app_path('Enums'),
            )
            ->replaceType(\DateTimeInterface::class, 'string')
            ->replaceType(UploadedFile::class, 'File');
    }

    /**
     * @return array<RouteFilter>
     */
    private function apiRouteFilters(): array
    {
        return [
            new ApiRoutePrefixFilter,
            new ControllerRouteFilter(
                'Filament\\*',
                'Illuminate\\*',
                'Laravel\\*',
                'Livewire\\*',
                'Spatie\\*',
                'App\\Http\\Controllers\\Admin\\*',
                'App\\Http\\Controllers\\Legacy\\*',
            ),
            new NamedRouteFilter(
                'boost.browser-logs',
                'filament.*',
                'generated::*',
                'horizon.*',
                'livewire.*',
                'login',
                'logout',
                'password.*',
                'register*',
                'sanctum.*',
                'scribe.*',
                'storage.local.*',
                'verification.*',
                'webhook-client-*',
            ),
        ];
    }

    /**
     * @return array<string>
     */
    private function routeDirectories(): array
    {
        return [
            base_path('routes/api.php'),
            base_path('routes/api'),
            app_path('Providers'),
        ];
    }
}
