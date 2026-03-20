<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use App\Http\Controllers\Me\CartController;
use App\Http\Controllers\Me\OrderController;
use App\Http\Controllers\Me\FollowController;
use App\Http\Controllers\Me\ProductController;
use App\Http\Controllers\Me\VendorOrderController;
use App\Http\Controllers\Me\ProductImageController;
use App\Http\Controllers\Me\ProductStatusController;
use App\Http\Controllers\Me\VendorProfileController;
use App\Http\Controllers\Me\SizeTemplateItemController;
use App\Http\Controllers\Public\VendorReviewController;
use App\Http\Controllers\Me\LivestreamProductController;
use App\Http\Controllers\Public\ProductReviewController;
use App\Http\Controllers\Public\LivestreamCommentController;
use App\Http\Controllers\Public\ShortVideoCommentController;

it('keeps buyer and vendor API routes registered after splitting route files', function (): void {
    $routes = app('router')->getRoutes();

    expect(ltrim($routes->match(Request::create('/api/v1/cart', 'GET'))->getActionName(), '\\'))
        ->toBe(CartController::class.'@index');

    expect(ltrim($routes->match(Request::create('/api/v1/orders', 'POST'))->getActionName(), '\\'))
        ->toBe(OrderController::class.'@store');

    expect(ltrim($routes->match(Request::create('/api/v1/me/products', 'GET'))->getActionName(), '\\'))
        ->toBe(ProductController::class.'@index');

    expect(ltrim($routes->match(Request::create('/api/v1/me/products/1/status', 'PATCH'))->getActionName(), '\\'))
        ->toBe(ProductStatusController::class.'@update');

    expect(ltrim($routes->match(Request::create('/api/v1/me/products/1/images/1', 'DELETE'))->getActionName(), '\\'))
        ->toBe(ProductImageController::class.'@destroy');

    expect(ltrim($routes->match(Request::create('/api/v1/me/size-templates/1/items', 'POST'))->getActionName(), '\\'))
        ->toBe(SizeTemplateItemController::class.'@store');

    expect(ltrim($routes->match(Request::create('/api/v1/me/livestreams/1/products', 'POST'))->getActionName(), '\\'))
        ->toBe(LivestreamProductController::class.'@store');

    expect(ltrim($routes->match(Request::create('/api/v1/me/vendor-orders', 'GET'))->getActionName(), '\\'))
        ->toBe(VendorOrderController::class.'@index');

    expect(ltrim($routes->match(Request::create('/api/v1/me/followings', 'GET'))->getActionName(), '\\'))
        ->toBe(FollowController::class.'@followings');

    expect(ltrim($routes->match(Request::create('/api/v1/me/vendors', 'GET'))->getActionName(), '\\'))
        ->toBe(VendorProfileController::class.'@show');

    expect(ltrim($routes->match(Request::create('/api/v1/me/balances', 'GET'))->getActionName(), '\\'))
        ->toBe(VendorProfileController::class.'@balance');

    expect(ltrim($routes->match(Request::create('/api/v1/me/followings', 'GET'))->getActionName(), '\\'))
        ->toBe(FollowController::class.'@followings');

    expect(ltrim($routes->match(Request::create('/api/v1/me/vendors', 'GET'))->getActionName(), '\\'))
        ->toBe(VendorProfileController::class.'@show');

    expect(ltrim($routes->match(Request::create('/api/v1/me/balances', 'GET'))->getActionName(), '\\'))
        ->toBe(VendorProfileController::class.'@balance');

    expect(ltrim($routes->match(Request::create('/api/v1/products/1/reviews', 'GET'))->getActionName(), '\\'))
        ->toBe(ProductReviewController::class.'@index');

    expect(ltrim($routes->match(Request::create('/api/v1/vendors/1/reviews', 'GET'))->getActionName(), '\\'))
        ->toBe(VendorReviewController::class.'@index');

    expect(ltrim($routes->match(Request::create('/api/v1/short-videos/1/comments', 'GET'))->getActionName(), '\\'))
        ->toBe(ShortVideoCommentController::class.'@index');

    expect(ltrim($routes->match(Request::create('/api/v1/livestreams/1/comments', 'GET'))->getActionName(), '\\'))
        ->toBe(LivestreamCommentController::class.'@index');
});
