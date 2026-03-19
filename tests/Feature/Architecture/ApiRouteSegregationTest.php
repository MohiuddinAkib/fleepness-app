<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use App\Http\Controllers\Me\CartController;
use App\Http\Controllers\Me\OrderController;
use App\Http\Controllers\Me\FollowController;
use App\Http\Controllers\Me\ProductController;
use App\Http\Controllers\Me\VendorOrderController;
use App\Http\Controllers\Me\ProductStatusController;
use App\Http\Controllers\Me\VendorProfileController;

it('keeps buyer and vendor API routes registered after splitting route files', function (): void {
    $routes = app('router')->getRoutes();

    expect(ltrim($routes->match(Request::create('/api/cart', 'GET'))->getActionName(), '\\'))
        ->toBe(CartController::class.'@index');

    expect(ltrim($routes->match(Request::create('/api/orders', 'POST'))->getActionName(), '\\'))
        ->toBe(OrderController::class.'@store');

    expect(ltrim($routes->match(Request::create('/api/me/products', 'GET'))->getActionName(), '\\'))
        ->toBe(ProductController::class.'@index');

    expect(ltrim($routes->match(Request::create('/api/me/products/1/status', 'PATCH'))->getActionName(), '\\'))
        ->toBe(ProductStatusController::class.'@update');

    expect(ltrim($routes->match(Request::create('/api/me/vendor-orders', 'GET'))->getActionName(), '\\'))
        ->toBe(VendorOrderController::class.'@index');

    expect(ltrim($routes->match(Request::create('/api/me/followings', 'GET'))->getActionName(), '\\'))
        ->toBe(FollowController::class.'@followings');

    expect(ltrim($routes->match(Request::create('/api/me/vendors', 'GET'))->getActionName(), '\\'))
        ->toBe(VendorProfileController::class.'@show');

    expect(ltrim($routes->match(Request::create('/api/me/balances', 'GET'))->getActionName(), '\\'))
        ->toBe(VendorProfileController::class.'@balance');

    expect(ltrim($routes->match(Request::create('/api/v1/me/followings', 'GET'))->getActionName(), '\\'))
        ->toBe(FollowController::class.'@followings');

    expect(ltrim($routes->match(Request::create('/api/v1/me/vendors', 'GET'))->getActionName(), '\\'))
        ->toBe(VendorProfileController::class.'@show');

    expect(ltrim($routes->match(Request::create('/api/v1/me/balances', 'GET'))->getActionName(), '\\'))
        ->toBe(VendorProfileController::class.'@balance');
});
