<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

it('generates echo notification payload types from broadcast events', function (): void {
    $path = storage_path('framework/testing/wayfinder-echo-types');

    File::deleteDirectory($path);

    $this->artisan('app:generate-echo-payload-types', [
        '--path' => $path,
    ])->assertSuccessful();

    $generatedFile = "{$path}/echo-notification-payloads.ts";

    expect(File::exists($generatedFile))->toBeTrue()
        ->and(File::get($generatedFile))->toContain('.new_order_for_vendor')
        ->and(File::get($generatedFile))->toContain('.customer_order_status_changed')
        ->and(File::get($generatedFile))->toContain('EchoNotificationPayloads');
});
