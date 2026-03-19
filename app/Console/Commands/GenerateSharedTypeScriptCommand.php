<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Console\Kernel;
use Spatie\TypeScriptTransformer\TypeScriptTransformerConfig;

class GenerateSharedTypeScriptCommand extends Command
{
    protected $signature = 'app:generate-shared-types {--path=}';

    protected $description = 'Generate shared TypeScript API contract types and sync them to the frontend app';

    public function __construct(
        private readonly Filesystem $files,
        private readonly Kernel $kernel,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        config()->set('cache.default', 'array');
        config()->set('data.structure_caching.enabled', false);

        /** @var TypeScriptTransformerConfig $config */
        $config = app(TypeScriptTransformerConfig::class);

        $destination = $this->destinationPath();

        $this->files->deleteDirectory($config->outputDirectory);
        $this->files->ensureDirectoryExists($config->outputDirectory);

        $transformExitCode = $this->kernel->call('typescript:transform');

        if (self::SUCCESS !== $transformExitCode) {
            $this->error('Failed to generate TypeScript transformer output.');

            return self::FAILURE;
        }

        $this->files->deleteDirectory($destination);
        $this->files->ensureDirectoryExists($destination);
        $this->files->copyDirectory($config->outputDirectory, $destination);
        $this->normalizeGeneratedRouteHelper($destination);
        $this->normalizeGeneratedControllerFiles($destination);

        $echoExitCode = $this->kernel->call('app:generate-echo-payload-types', [
            '--path' => $destination,
        ]);

        if (self::SUCCESS !== $echoExitCode) {
            $this->error('Generated shared API types, but failed to append Echo payload types.');

            return self::FAILURE;
        }

        $this->normalizeGeneratedEchoPayloadTypes($destination);

        $this->info("Shared TypeScript types written to [{$destination}].");

        return self::SUCCESS;
    }

    private function normalizeGeneratedRouteHelper(string $destination): void
    {
        $routeHelperPath = "{$destination}/helpers/route.ts";

        if (! $this->files->exists($routeHelperPath)) {
            return;
        }

        $content = $this->files->get($routeHelperPath);

        $content = str_replace(
            <<<'TS'
if (absolute) {
    url = window.location.origin + url;
}
TS,
            <<<'TS'
if (absolute) {
    const origin = typeof globalThis !== 'undefined' && 'location' in globalThis
        ? (globalThis as { location?: { origin?: string } }).location?.origin
        : undefined;

    if (origin) {
        url = origin + url;
    }
}
TS,
            $content,
        );

        $this->files->put($routeHelperPath, $content);
    }

    private function normalizeGeneratedControllerFiles(string $destination): void
    {
        $controllersPath = "{$destination}/controllers";

        if (! $this->files->isDirectory($controllersPath)) {
            return;
        }

        foreach ($this->files->allFiles($controllersPath) as $file) {
            if ('ts' !== $file->getExtension()) {
                continue;
            }

            $content = $this->files->get($file->getPathname());

            if (! str_contains($content, 'export const ')) {
                continue;
            }

            $this->files->put(
                $file->getPathname(),
                $this->mergeDuplicateControllerDeclarations($content),
            );
        }
    }

    private function normalizeGeneratedEchoPayloadTypes(string $destination): void
    {
        $path = "{$destination}/echo-notification-payloads.ts";

        if (! $this->files->exists($path)) {
            return;
        }

        $content = $this->files->get($path);

        $content = str_replace(
            [
                'App.Models.Transaction',
                'App.Models.VendorOrder',
            ],
            [
                'App.Data.TransactionData',
                'App.Data.VendorOrderData',
            ],
            $content,
        );

        $this->files->put($path, $content);
    }

    private function mergeDuplicateControllerDeclarations(string $content): string
    {
        $lines = preg_split('/\R/', $content) ?: [];

        $prefixLines = [];
        $constBlocks = [];
        $namespaceBlocks = [];
        $controllerNames = [];
        $index = 0;

        while ($index < count($lines) && ! str_starts_with($lines[$index], 'export const ') && ! str_starts_with($lines[$index], 'export namespace ')) {
            $prefixLines[] = $lines[$index];
            $index++;
        }

        while ($index < count($lines)) {
            $line = $lines[$index];

            if (1 === preg_match('/^export const (?<name>[A-Za-z0-9_]+) = \{$/', $line, $matches)) {
                $name = $matches['name'];
                $body = [];
                $index++;

                while ($index < count($lines) && '} as const' !== $lines[$index]) {
                    $body[] = $lines[$index];
                    $index++;
                }

                $constBlocks[$name] ??= [];
                $controllerNames[$name] ??= count($controllerNames);

                foreach ($this->parseConstMethods($body) as $method => $methodLines) {
                    $constBlocks[$name][$method] = $methodLines;
                }
            }

            if (1 === preg_match('/^export namespace (?<name>[A-Za-z0-9_]+) \{$/', $line, $matches)) {
                $name = $matches['name'];
                $body = [];
                $braceDepth = 1;
                $index++;

                while ($index < count($lines) && 0 < $braceDepth) {
                    $currentLine = $lines[$index];
                    $braceDepth += substr_count($currentLine, '{');
                    $braceDepth -= substr_count($currentLine, '}');

                    if (0 < $braceDepth) {
                        $body[] = $currentLine;
                    }

                    $index++;
                }

                $namespaceBlocks[$name] ??= [];
                $controllerNames[$name] ??= count($controllerNames);

                foreach ($this->parseNamespaceMethods($body) as $method => $methodLines) {
                    $namespaceBlocks[$name][$method] = $methodLines;
                }

                continue;
            }

            $index++;
        }

        $outputLines = $prefixLines;

        foreach (array_keys($controllerNames) as $name) {
            if (isset($constBlocks[$name])) {
                $outputLines[] = "export const {$name} = {";

                foreach ($constBlocks[$name] as $methodLines) {
                    foreach ($methodLines as $methodLine) {
                        $outputLines[] = $methodLine;
                    }
                }

                $outputLines[] = '} as const';
            }

            if (isset($namespaceBlocks[$name])) {
                $outputLines[] = "export namespace {$name} {";

                foreach ($namespaceBlocks[$name] as $methodLines) {
                    foreach ($methodLines as $methodLine) {
                        $outputLines[] = $methodLine;
                    }
                }

                $outputLines[] = '}';
            }
        }

        return implode(PHP_EOL, $outputLines).PHP_EOL;
    }

    /**
     * @param  array<int, string>  $lines
     * @return array<string, array<int, string>>
     */
    private function parseConstMethods(array $lines): array
    {
        $methods = [];
        $index = 0;

        while ($index < count($lines)) {
            $line = $lines[$index];

            if (1 !== preg_match('/^(?<name>[A-Za-z0-9_]+):/', trim($line), $matches)) {
                $index++;

                continue;
            }

            $name = $matches['name'];
            $methodLines = [$line];
            $depth = $this->typeScriptExpressionDepth($line);
            $index++;

            while ($index < count($lines)) {
                $currentLine = $lines[$index];
                $methodLines[] = $currentLine;
                $depth += $this->typeScriptExpressionDepth($currentLine);
                $index++;

                if (0 >= $depth && str_ends_with(trim($currentLine), ',')) {
                    break;
                }
            }

            $methods[$name] = $methodLines;
        }

        return $methods;
    }

    /**
     * @param  array<int, string>  $lines
     * @return array<string, array<int, string>>
     */
    private function parseNamespaceMethods(array $lines): array
    {
        $methods = [];
        $index = 0;

        while ($index < count($lines)) {
            $line = $lines[$index];

            if (1 !== preg_match('/^export namespace (?<name>[A-Za-z0-9_]+) \{$/', trim($line), $matches)) {
                $index++;

                continue;
            }

            $name = $matches['name'];
            $braceDepth = 1;
            $methodLines = [$line];
            $index++;

            while ($index < count($lines) && 0 < $braceDepth) {
                $currentLine = $lines[$index];
                $methodLines[] = $currentLine;
                $braceDepth += substr_count($currentLine, '{');
                $braceDepth -= substr_count($currentLine, '}');
                $index++;
            }

            $methods[$name] = $methodLines;
        }

        return $methods;
    }

    private function typeScriptExpressionDepth(string $line): int
    {
        return substr_count($line, '{')
            + substr_count($line, '(')
            + substr_count($line, '[')
            + substr_count($line, '<')
            - substr_count($line, '}')
            - substr_count($line, ')')
            - substr_count($line, ']')
            - substr_count($line, '>');
    }

    private function destinationPath(): string
    {
        $path = $this->option('path');

        if (is_string($path) && '' !== $path) {
            return $path;
        }

        return false !== realpath(base_path('../../js_projects/Fleepness.ltd'))
            ? base_path('../../js_projects/Fleepness.ltd/src/generated/backend')
            : storage_path('app/shared-types');
    }
}
