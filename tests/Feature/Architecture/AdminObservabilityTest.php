<?php

declare(strict_types=1);

use App\Models\Fee;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Filament\Resources\Fees\FeeResource;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Sliders\SliderResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Sections\SectionResource;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Transactions\TransactionResource;
use App\Filament\Resources\VendorOrders\VendorOrderResource;
use App\Filament\Resources\ShopCategories\ShopCategoryResource;
use App\Filament\Resources\PaymentMethods\PaymentMethodResource;
use App\Filament\Resources\VendorProfiles\VendorProfileResource;
use App\Filament\Resources\DeliveryOptions\DeliveryOptionResource;
use Rmsramos\Activitylog\RelationManagers\ActivitylogRelationManager;

uses(RefreshDatabase::class);

it('registers the admin activity log screen and horizon dashboard routes', function (): void {
    $routes = app('router')->getRoutes();

    expect($routes->match(Request::create('/admin/activitylogs', 'GET'))->uri())
        ->toBe('admin/activitylogs');

    expect($routes->match(Request::create('/horizon', 'GET'))->uri())
        ->toBe('horizon/{view?}');
});

it('adds activity log relation managers to admin resources', function (string $resourceClass): void {
    expect($resourceClass::getRelations())
        ->toContain(ActivitylogRelationManager::class);
})->with([
    CategoryResource::class,
    DeliveryOptionResource::class,
    FeeResource::class,
    OrderResource::class,
    PaymentMethodResource::class,
    ProductResource::class,
    SectionResource::class,
    ShopCategoryResource::class,
    SliderResource::class,
    TransactionResource::class,
    UserResource::class,
    VendorOrderResource::class,
    VendorProfileResource::class,
]);

it('records activity entries for admin managed models', function (): void {
    $fee = Fee::factory()->create([
        'vat' => 5,
        'platform_fee' => 10,
        'commission' => 15,
    ]);

    $fee->update([
        'vat' => 7,
    ]);

    $events = Activity::query()
        ->where('subject_type', $fee->getMorphClass())
        ->pluck('event')
        ->all();

    expect($events)
        ->toContain('created')
        ->toContain('updated');
});
