<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

it('generates shared typescript contracts for the frontend app repeatedly in the same process', function (): void {
    $path = storage_path('framework/testing/shared-types');

    File::deleteDirectory($path);

    $this->artisan('app:generate-shared-types', [
        '--path' => $path,
    ])->assertSuccessful();

    $this->artisan('app:generate-shared-types', [
        '--path' => $path,
    ])->assertSuccessful();

    expect(File::exists("{$path}/types.d.ts"))->toBeTrue()
        ->and(File::exists("{$path}/helpers/route.ts"))->toBeTrue()
        ->and(File::exists("{$path}/controllers/App/Http/Controllers/Me/index.ts"))->toBeTrue()
        ->and(File::exists("{$path}/echo-notification-payloads.ts"))->toBeTrue()
        ->and(File::get("{$path}/types.d.ts"))->toContain('App.Data.ProductData')
        ->and(File::get("{$path}/helpers/route.ts"))->toContain('api/v1/me/products/{product}/status')
        ->and(File::get("{$path}/helpers/route.ts"))->not->toContain('window.location.origin')
        ->and(File::get("{$path}/controllers/App/Http/Controllers/Auth/index.ts"))->toContain('App.Data.Auth.RegisterData')
        ->and(File::get("{$path}/controllers/App/Http/Controllers/Auth/index.ts"))->toContain('user: App.Data.UserData')
        ->and(File::get("{$path}/controllers/App/Http/Controllers/Me/index.ts"))->toContain('Spatie.LaravelData.PaginatedDataCollection<number, App.Data.OrderData>')
        ->and(File::get("{$path}/controllers/App/Http/Controllers/Public/index.ts"))->toContain('Array<App.Data.CategoryData>')
        ->and(File::get("{$path}/controllers/App/Http/Controllers/Public/index.ts"))->not->toContain('undefined<App.Data.')
        ->and(substr_count(File::get("{$path}/controllers/App/Http/Controllers/Me/index.ts"), 'export const OrderController = {'))->toBe(1)
        ->and(File::get("{$path}/echo-notification-payloads.ts"))->not->toContain("import type {App} from './types';")
        ->and(File::get("{$path}/echo-notification-payloads.ts"))->toContain('App.Data.VendorOrderData')
        ->and(File::get("{$path}/echo-notification-payloads.ts"))->toContain('App.Data.TransactionData');
});
